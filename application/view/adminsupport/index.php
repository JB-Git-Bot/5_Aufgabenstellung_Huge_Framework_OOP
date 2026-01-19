<?php
$tickets = $this->tickets ?? [];
?>

<h2>Admin: Support-Tickets</h2>
<hr>

<ul>
<?php if (!empty($tickets)): ?>
    <?php foreach ($tickets as $ticket): ?>
        <li>
            #<?= (int)$ticket->id; ?> |
            Kunde(User): <?= (int)$ticket->user_id; ?> |
            <?= htmlspecialchars((string)$ticket->subject); ?> |
            Status: <?= htmlspecialchars((string)$ticket->status); ?>
            <?php if (!empty($ticket->assigned_admin_id)): ?>
                | Assigned Admin: <?= (int)$ticket->assigned_admin_id; ?>
            <?php endif; ?>
            -
            <a href="<?= Config::get('URL'); ?>adminSupport/view/<?= (int)$ticket->id; ?>">Öffnen</a>
        </li>
    <?php endforeach; ?>
<?php else: ?>
    <li>Keine Tickets vorhanden</li>
<?php endif; ?>
</ul>
