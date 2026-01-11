<div class="container">
  <h1>Messenger</h1>

<?php
  $users = $users ?? ($this->users ?? []);
  $selectedUserId = $selectedUserId ?? ($this->selectedUserId ?? 0);
?>

  <?php if (empty($users)) : ?>
      <p>Keine anderen User gefunden. Lege z.B. demo2 an.</p>
  <?php else : ?>

  <div style="display:flex; gap:20px;">
    <!-- User-Liste -->
    <div style="width:220px; border:1px solid #ddd; padding:10px;">
      <strong>Benutzer</strong>
      <ul style="list-style:none; padding-left:0;">
        <?php foreach ($users as $u) : ?>
          <li style="margin:6px 0;">
            <a href="#" onclick="selectUser(<?php echo (int)$u->user_id; ?>); return false;">
              <?php echo htmlspecialchars($u->user_name, ENT_QUOTES, 'UTF-8'); ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <!-- Chat -->
    <div style="flex:1; border:1px solid #ddd; padding:10px;">
      <div id="chatBox" style="height:300px; overflow:auto; border:1px solid #ccc; padding:10px;"></div>

      <form id="chatForm" style="margin-top:10px; display:flex; gap:10px;">
        <input type="hidden" id="receiver_id" value="<?php echo (int)$selectedUserId; ?>">
        <input id="content" type="text" style="flex:1;" placeholder="Nachricht eingeben...">
        <button type="submit">Senden</button>
      </form>
    </div>
  </div>

  <?php endif; ?>
</div>

<script>
let currentReceiverId = document.getElementById('receiver_id') ? document.getElementById('receiver_id').value : 0;
const chatBox = document.getElementById('chatBox');
const form = document.getElementById('chatForm');
const contentInput = document.getElementById('content');

function escapeHtml(text) {
  return text.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
}

async function loadConversation() {
  if (!currentReceiverId || !chatBox) return;

  const res = await fetch('<?php echo Config::get("URL"); ?>messenger/conversation?user_id=' + currentReceiverId);
  const data = await res.json();
  if (!data.ok) return;

  chatBox.innerHTML = '';
  data.messages.forEach(msg => {
    const line = document.createElement('div');
    const time = msg.created_at ? msg.created_at : '';
    line.innerHTML = '<strong>' + escapeHtml(msg.sender_name) + '</strong> [' + escapeHtml(time) + ']: ' + escapeHtml(msg.content);
    chatBox.appendChild(line);
  });

  chatBox.scrollTop = chatBox.scrollHeight;
}

function selectUser(userId) {
  currentReceiverId = userId;
  document.getElementById('receiver_id').value = userId;
  loadConversation();
}

if (form) {
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const content = contentInput.value.trim();
    if (!content) return;

    const body = new FormData();
    body.append('receiver_id', currentReceiverId);
    body.append('content', content);

    const res = await fetch('<?php echo Config::get("URL"); ?>messenger/send', { method: 'POST', body });
    const data = await res.json();

    if (data.ok) {
      contentInput.value = '';
      loadConversation();
    } else {
      alert(data.error || 'Fehler beim Senden');
    }
  });
}

// initial + polling
loadConversation();
setInterval(loadConversation, 2000);
</script>
