<?php
// HUge-typisch: Daten kommen als $this->ticket / $this->messages
$ticket = $this->ticket ?? null;
$messages = $this->messages ?? [];
?>

<?php if (empty($ticket)) : ?>
    <h2>Ticket nicht gefunden</h2>
    <p>Entweder existiert dieses Ticket nicht oder du hast keinen Zugriff.</p>
    <a href="<?= Config::get('URL'); ?>support">← Zurück</a>
    <?php return; ?>
<?php endif; ?>

<h2>Ticket: <?= htmlspecialchars((string)$ticket->subject); ?></h2>
<p>Status: <?= htmlspecialchars((string)$ticket->status); ?></p>

<hr>

<h3>Nachrichten</h3>

<ul>
    <?php if (!empty($messages)): ?>
        <?php foreach ($messages as $msg): ?>
            <li>
                <strong>User <?= (int)$msg->sender_user_id; ?>:</strong>
                <?= nl2br(htmlspecialchars((string)$msg->message)); ?>
                <br><small><?= htmlspecialchars((string)$msg->created_at); ?></small>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>Noch keine Nachrichten vorhanden</li>
    <?php endif; ?>
</ul>

<hr>

<h3>Antwort schreiben</h3>
<form method="post" action="<?= Config::get('URL'); ?>support/addMessage/<?= (int)$ticket->id; ?>">
    <textarea name="message" rows="4" required></textarea><br>
    <button type="submit">Senden</button>
</form>

<br>
<a href="<?= Config::get('URL'); ?>support">← Zurück</a>
