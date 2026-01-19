<?php
// HUge-typisch: Daten kommen als $this->tickets
$tickets = $this->tickets ?? [];
?>

<h2>Meine Support-Tickets</h2>

<form method="post" action="<?= Config::get('URL'); ?>support/create">
    <input type="text" name="subject" placeholder="Ticket-Betreff" required>
    <button type="submit">Ticket erstellen</button>
</form>

<hr>

<ul>
<?php if (!empty($tickets)): ?>
    <?php foreach ($tickets as $ticket): ?>
        <li>
            <?= htmlspecialchars((string)$ticket->subject); ?>
            (Status: <?= htmlspecialchars((string)$ticket->status); ?>)
            -
            <a href="<?= Config::get('URL'); ?>support/view/<?= (int)$ticket->id; ?>">
                Öffnen
            </a>
        </li>
    <?php endforeach; ?>
<?php else: ?>
    <li>Noch keine Tickets vorhanden</li>
<?php endif; ?>
</ul>
