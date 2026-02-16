<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

class Form
{
    /**
     * Handle frontend form submission.
     */
    public static function handleSubmission(): void
    {
        $formId = (int)($_POST['form_id'] ?? 0);
        if (!$formId) {
            self::respond(false, 'Neplatný formulář.');
        }

        $db = Database::getInstance();
        $form = $db->fetchOne("SELECT * FROM zvele_forms WHERE id = ?", [$formId]);
        if (!$form) {
            self::respond(false, 'Formulář nenalezen.');
        }

        // Honeypot check
        $honeypot = $form['honeypot_field'] ?? 'website_url';
        if (!empty($_POST[$honeypot])) {
            self::respond(true, $form['success_message']);
        }

        // Rate limiting
        $ip = client_ip();
        $rateKey = 'form_' . $ip;
        $limit = (int)setting('form_rate_limit', 5);

        if (!Security::checkRateLimit($rateKey, $limit, 3600)) {
            self::respond(false, 'Příliš mnoho odeslání. Zkuste to později.');
        }

        Security::recordRateLimit($rateKey);

        // Collect field data
        $fields = json_decode($form['fields'], true) ?? [];
        $submittedData = $_POST['fields'] ?? [];
        $data = [];

        foreach ($fields as $field) {
            $name = $field['name'] ?? '';
            $value = trim($submittedData[$name] ?? '');

            if (!empty($field['required']) && $value === '') {
                self::respond(false, 'Vyplňte prosím pole "' . ($field['label'] ?? $name) . '".');
            }

            if ($field['type'] === 'email' && $value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                self::respond(false, 'Zadejte platný e-mail.');
            }

            $data[$name] = Security::sanitize($value);
        }

        // Save submission
        $db->insert('zvele_form_submissions', [
            'form_id' => $formId,
            'data' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'ip_address' => $ip,
            'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
        ]);

        // Send email notification
        self::sendEmail($form, $data);

        self::respond(true, $form['success_message'] ?? 'Děkujeme, zpráva byla odeslána.');
    }

    /**
     * Send email notification about form submission.
     */
    private static function sendEmail(array $form, array $data): void
    {
        $to = $form['email_to'];
        $subject = $form['email_subject'] ?? 'Nová zpráva z formuláře';
        $from = setting('form_email_from', 'noreply@example.cz');

        $body = "Nové odeslání formuláře: {$form['name']}\n\n";
        foreach ($data as $key => $value) {
            $body .= ucfirst(str_replace('_', ' ', $key)) . ": {$value}\n";
        }
        $body .= "\nOdesláno: " . date('j. n. Y H:i:s') . "\n";
        $body .= "IP: " . client_ip() . "\n";

        $headers = [
            'From: ' . $from,
            'Reply-To: ' . ($data['email'] ?? $from),
            'Content-Type: text/plain; charset=UTF-8',
            'X-Mailer: ZveleCMS',
        ];

        @mail($to, $subject, $body, implode("\r\n", $headers));
    }

    /**
     * Respond to form submission (redirect with flash or JSON for AJAX).
     */
    private static function respond(bool $success, string $message): never
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }

        flash($success ? 'success' : 'error', $message);
        $referer = $_SERVER['HTTP_REFERER'] ?? url('/');
        redirect($referer);
    }
}
