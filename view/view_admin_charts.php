<page_admin_charts>
    <?php if (($_SESSION['login_status'] ?? 0) && isset($_SESSION['user']) && (int)($_SESSION['user']['is_admin'] ?? 0) == 1): ?>
    <?php
    $filters = $_page_view['filters'] ?? [];
    $from    = $filters['from']   ?? '';
    $to      = $filters['to']     ?? '';
    $sort    = $filters['sort']   ?? 'desc';
    $limit   = (int)($filters['limit']  ?? 15);
    $user_id = (int)($filters['user_id'] ?? 0);
    $mr = $_page_view['monthly_revenue'] ?? [];
    $cs = $_page_view['category_share'] ?? [];
    $tc = $_page_view['top_customers'] ?? [];

    $mrLabels = array_column($mr, 'ym');
    $mrData   = array_map('floatval', array_column($mr, 'revenue'));

    $csLabels = array_column($cs, 'category');
    $csData   = array_map('floatval', array_column($cs, 'revenue'));

    $tcLabels = array_map(function($r){ return $r['username'] . (!empty($r['email']) ? " ({$r['email']})" : ""); }, $tc);
    $tcData   = array_map('floatval', array_column($tc, 'spent'));

    $top  = $_page_view['top_products'] ?? [];
    $big  = $_page_view['big_orders'] ?? [];

    // Labels/data za grafike
    $tlab = array_column($top, 'product_name');
    $tval = array_map('intval', array_column($top, 'sold_qty'));

    $olab = array_map(function($r){
        $tag = 'Order #'.$r['order_id'].' ('.$r['created'].')';
        if (!empty($r['username'])) $tag .= ' — '.$r['username'];
        return $tag;
    }, $big);
    $oval = array_map('floatval', array_column($big, 'total_value'));
    ?>
        <h2>Statistika (filtrirano)</h2>

        <form method="POST" class="charts-filter" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:end;">
            <div>
                <label>Od datuma</label>
                <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
            </div>
            <div>
                <label>Do datuma</label>
                <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
            </div>
            <div>
                <label>User ID (opciono)</label>
                <input type="number" name="user_id" min="0" value="<?= $user_id ?: '' ?>" placeholder="npr. 11">
            </div>
            <div>
                <label>Sort (po vrednosti)</label>
                <select name="sort">
                    <option value="desc" <?= $sort==='desc'?'selected':'' ?>>Najveće prvo</option>
                    <option value="asc"  <?= $sort==='asc'?'selected':''  ?>>Najmanje prvo</option>
                </select>
            </div>
            <div>
                <label>Limit</label>
                <select name="limit">
                    <?php foreach ([5,10,15,20,30,50] as $lim): ?>
                        <option value="<?= $lim ?>" <?= $limit===$lim?'selected':'' ?>><?= $lim ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button>Primeni filtere</button>
        </form>

        <div class="charts" style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-top:1rem;">
            <div class="chart-holder" style="background:#fff; border:1px solid #eee; border-radius:6px; padding:1rem;">
                <h3 style="margin:0 0 .5rem;">Top proizvodi (po komadima)</h3>
                <canvas id="chartTop"></canvas>
            </div>
            <div class="chart-holder" style="background:#fff; border:1px solid #eee; border-radius:6px; padding:1rem;">
                <h3 style="margin:0 0 .5rem;">Najveće porudžbine (po vrednosti)</h3>
                <canvas id="chartOrders"></canvas>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const topLabels = <?= json_encode($tlab, JSON_UNESCAPED_UNICODE) ?>;
        const topData   = <?= json_encode($tval) ?>;

        new Chart(document.getElementById('chartTop'), {
            type: 'bar',
            data: { labels: topLabels, datasets: [{ label: 'Prodatih komada', data: topData }] }
        });

        const orderLabels = <?= json_encode($olab, JSON_UNESCAPED_UNICODE) ?>;
        const orderData   = <?= json_encode($oval) ?>;

        new Chart(document.getElementById('chartOrders'), {
            type: 'line',
            data: { labels: orderLabels, datasets: [{ label: 'Vrednost porudžbine (RSD)', data: orderData, tension: 0.3 }] }
        });
    </script>
    <div class="charts" style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-top:1rem;">
        <div class="chart-holder" style="background:#fff; border:1px solid #eee; border-radius:6px; padding:1rem;">
            <h3 style="margin:0 0 .5rem;">Ukupna prodaja po mesecima</h3>
            <canvas id="chartMonthly"></canvas>
        </div>
        <div class="chart-holder" style="background:#fff; border:1px solid #eee; border-radius:6px; padding:1rem;">
            <h3 style="margin:0 0 .5rem;">Udeo kategorija u prodaji</h3>
            <canvas id="chartCategories"></canvas>
        </div>
        <div class="chart-holder" style="background:#fff; border:1px solid #eee; border-radius:6px; padding:1rem; grid-column:1 / -1;">
            <h3 style="margin:0 0 .5rem;">Top kupci (po vrednosti)</h3>
            <canvas id="chartTopCustomers"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const monthlyLabels = <?= json_encode($mrLabels, JSON_UNESCAPED_UNICODE) ?>;
        const monthlyData   = <?= json_encode($mrData) ?>;

        new Chart(document.getElementById('chartMonthly'), {
            type: 'line',
            data: { labels: monthlyLabels, datasets: [{ label: 'Prihod (RSD)', data: monthlyData, tension: 0.3 }] }
        });

        const catLabels = <?= json_encode($csLabels, JSON_UNESCAPED_UNICODE) ?>;
        const catData   = <?= json_encode($csData) ?>;

        new Chart(document.getElementById('chartCategories'), {
            type: 'pie',
            data: { labels: catLabels, datasets: [{ label: 'Prihod (RSD)', data: catData }] }
        });

        const custLabels = <?= json_encode($tcLabels, JSON_UNESCAPED_UNICODE) ?>;
        const custData   = <?= json_encode($tcData) ?>;

        new Chart(document.getElementById('chartTopCustomers'), {
            type: 'bar',
            data: { labels: custLabels, datasets: [{ label: 'Ukupna potrošnja (RSD)', data: custData }] },
            options: { indexAxis: 'y' }
        });
    </script>
    <?php else: ?>
        <?=$_error[] = "Nemate pristup."?>
    <?php endif; ?>
</page_admin_charts>
