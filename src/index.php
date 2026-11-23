<?php
$m = new mysqli("proxysql", "root", "rootpasswod", "kedaikopi", 6033);
if ($m->connect_error) { die("ERR:" . $m->connect_error); }
$r = $m->query("SELECT id, nama_produk, harga, stok FROM menu");
$items = [];
while ($r && ($row = $r->fetch_assoc())) {
  $items[] = [
    "id" => (int)$row["id"],
    "nama_produk" => $row["nama_produk"],
    "harga" => (int)$row["harga"],
    "stok" => (int)$row["stok"]
  ];
}
if ($r) { $r->free(); }
$m->close();
$totalMenu = count($items);
$totalStok = array_sum(array_column($items, "stok"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kopi Kenangan</title>
  <style>
    :root { --bg:#140f0c; --surface:#211813; --surface-2:#2b1f19; --ink:#f6efe9; --muted:#c7b6a8; --accent:#d99b52; --border:rgba(246,239,233,.12); --ok:#34d399; }
    * { box-sizing:border-box; margin:0; padding:0; }
    body { font-family:"Segoe UI",system-ui,sans-serif; background:radial-gradient(circle at top right,#2e1e15 0%,var(--bg) 55%); color:var(--ink); min-height:100vh; line-height:1.6; }
    .wrap { max-width:1040px; margin:0 auto; padding:28px 20px 60px; }
    .topbar { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:12px; padding-bottom:18px; border-bottom:1px solid var(--border); }
    .brand { font-weight:700; color:var(--accent); letter-spacing:.5px; }
    .badge { display:inline-flex; align-items:center; gap:8px; padding:6px 14px; border-radius:999px; font-size:.85rem; font-weight:600; background:rgba(52,211,153,.12); color:var(--ok); border:1px solid rgba(52,211,153,.35); }
    .dot { width:8px; height:8px; border-radius:50%; background:var(--ok); }
    .hero { margin-top:24px; padding:28px; border-radius:20px; background:linear-gradient(135deg,rgba(217,155,82,.15),rgba(33,24,19,.92)); border:1px solid var(--border); }
    .hero h1 { font-size:clamp(2rem,4.5vw,3rem); line-height:1.15; margin-bottom:12px; }
    .story { max-width:68ch; color:var(--muted); margin-bottom:20px; }
    .stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:12px; }
    .stat { background:var(--surface); border:1px solid var(--border); border-radius:14px; padding:14px; }
    .stat small { display:block; color:var(--muted); font-size:.78rem; text-transform:uppercase; }
    .stat b { font-size:1.35rem; color:var(--accent); }
    .controls { margin-top:24px; display:flex; flex-wrap:wrap; gap:10px; justify-content:space-between; align-items:center; }
    .search { flex:1 1 240px; padding:10px 14px; border-radius:10px; border:1px solid var(--border); background:var(--surface); color:var(--ink); }
    .btns { display:flex; flex-wrap:wrap; gap:8px; }
    button.btn { padding:9px 13px; border-radius:10px; border:1px solid var(--border); background:var(--surface); color:var(--ink); font-weight:600; cursor:pointer; }
    button.btn.active, button.btn:hover { background:var(--accent); color:#19110c; border-color:var(--accent); }
    .grid { margin-top:20px; display:grid; grid-template-columns:repeat(auto-fill,minmax(230px,1fr)); gap:14px; }
    .card { background:var(--surface); border:1px solid var(--border); border-radius:14px; padding:16px; cursor:pointer; }
    .card:hover { border-color:var(--accent); }
    .price { font-size:1.1rem; font-weight:700; color:var(--accent); margin-top:10px; }
    table { width:100%; border-collapse:collapse; margin-top:20px; background:var(--surface); border-radius:12px; overflow:hidden; }
    th, td { padding:12px 14px; text-align:left; border-bottom:1px solid var(--border); }
    th { background:var(--surface-2); color:var(--accent); }
    .note { margin-top:16px; padding:12px 16px; border-radius:10px; background:var(--surface-2); border-left:4px solid var(--accent); color:var(--muted); }
    .hidden { display:none !important; }
  </style>
</head>
<body>
  <div class="wrap">
    <header class="topbar">
      <div class="brand">KOPI KENANGAN</div>
      <div class="badge"><span class="dot"></span>ProxySQL OK</div>
    </header>

    <section class="hero">
      <h1>Kopi Kenangan</h1>
      <p class="story">Di balik setiap tegukan hangat, ada rasa yang hilang tetapi tidak untuk kenangannya. Setiap cangkir hadir membawa cerita lama yang tetap tinggal, menemani langkah hari ini dengan rasa yang tak pernah benar-benar pergi.</p>
      <div class="stats">
        <div class="stat"><small>Total Menu</small><b><?= $totalMenu ?> Item</b></div>
        <div class="stat"><small>Total Stok</small><b><?= $totalStok ?> Porsi</b></div>
        <div class="stat"><small>Database</small><b>ProxySQL :6033</b></div>
      </div>
    </section>

    <div class="controls">
      <input id="q" class="search" type="text" placeholder="Cari menu..." />
      <div class="btns">
        <button class="btn active" data-f="all">Semua</button>
        <button class="btn" data-f="high">Stok &ge; 30</button>
        <button class="btn" data-f="low">Stok &lt; 30</button>
        <button class="btn" id="viewBtn" type="button">Mode Tabel</button>
      </div>
    </div>

    <div id="gridView" class="grid"></div>
    <div id="tableView" class="hidden">
      <table><thead><tr><th>ID</th><th>Nama Produk</th><th>Harga</th><th>Stok</th></tr></thead><tbody id="tbody"></tbody></table>
    </div>
    <div id="detail" class="note">Klik salah satu menu untuk melihat ringkasan detailnya.</div>
  </div>

  <script>
    const data = <?= json_encode($items, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
    let mode = "all", tableMode = false;
    const rp = (n) => "Rp " + Number(n).toLocaleString("id-ID");

    function pickMenu(id) {
      const x = data.find((i) => i.id === id);
      if (x) document.getElementById("detail").innerHTML = `<strong>${x.nama_produk}</strong> (#${x.id}) — <strong>${rp(x.harga)}</strong>, stok <strong>${x.stok}</strong>.`;
    }

    function draw() {
      const q = document.getElementById("q").value.trim().toLowerCase();
      const list = data.filter((i) => {
        const okText = i.nama_produk.toLowerCase().includes(q) || String(i.id).includes(q);
        if (!okText) return false;
        if (mode === "high") return i.stok >= 30;
        if (mode === "low") return i.stok < 30;
        return true;
      });
      document.getElementById("gridView").innerHTML = list.length ? list.map((i) =>
        `<div class="card" onclick="pickMenu(${i.id})"><small>#${i.id} · Stok ${i.stok}</small><h3>${i.nama_produk}</h3><div class="price">${rp(i.harga)}</div></div>`
      ).join("") : `<div class="note">Menu tidak ditemukan.</div>`;
      document.getElementById("tbody").innerHTML = list.length ? list.map((i) =>
        `<tr onclick="pickMenu(${i.id})" style="cursor:pointer;"><td>${i.id}</td><td>${i.nama_produk}</td><td>${rp(i.harga)}</td><td>${i.stok}</td></tr>`
      ).join("") : `<tr><td colspan="4">Menu tidak ditemukan.</td></tr>`;
    }

    document.getElementById("q").addEventListener("input", draw);
    document.querySelectorAll("button.btn[data-f]").forEach((b) => b.addEventListener("click", () => {
      document.querySelectorAll("button.btn[data-f]").forEach((x) => x.classList.remove("active"));
      b.classList.add("active"); mode = b.dataset.f; draw();
    }));
    document.getElementById("viewBtn").addEventListener("click", (e) => {
      tableMode = !tableMode;
      document.getElementById("gridView").classList.toggle("hidden", tableMode);
      document.getElementById("tableView").classList.toggle("hidden", !tableMode);
      e.target.textContent = tableMode ? "Mode Kartu" : "Mode Tabel";
      e.target.classList.toggle("active", tableMode);
    });
    draw();
  </script>
</body>
</html>