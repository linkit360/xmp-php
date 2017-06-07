<?php
use yii\helpers\Url;
use yii\helpers\Html;

$permissions = Yii::$app->getAuthManager()->getPermissionsByUser(Yii::$app->user->id);
$permissions = array_keys($permissions);

$menu = [];
$menu[] = [
    'name' => '<i class="fa fa-th-large"></i> Dashboard',
    'url' => '/',
];

# Reports
$group = 'Reports';
$menu[$group] = [
    'name' => '<i class="fa fa-bar-chart-o"></i> ' . $group,
    'items' => [],
];

if (in_array('reportsAdvertisingView', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Advertising',
        'url' => 'reports/index',
    ];
}

if (in_array('reportsConversionView', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Conversion',
        'url' => 'reports/conversion',
    ];
}

if (in_array('reportsRevenueView', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Revenue',
        'url' => 'reports/revenue',
    ];
}

# Campaigns
$group = 'Campaigns';
$menu[$group] = [
    'name' => '<i class="fa fa-list"></i> ' . $group,
    'items' => [],
];

if (in_array('campaignsManage', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Campaigns Management',
        'url' => 'campaigns/index',
    ];
}

if (in_array('lpCreate', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'LP Management',
        'url' => 'landing-page/index',
    ];
}

if (in_array('campaignsManage', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Services Management',
        'url' => 'services/index',
    ];
}

if (in_array('contentManage', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Content Management',
        'url' => 'content/index',
    ];
}

# Tools
$group = 'Tools';
$menu[$group] = [
    'name' => '<i class="fa fa-wrench"></i> ' . $group,
    'items' => [],
];

if (in_array('blacklistManage', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Blacklist',
        'url' => 'blacklist/index',
    ];
}

# Logs
$group = 'Logs';
$menu[$group] = [
    'name' => '<i class="fa fa-table"></i> ' . $group,
    'items' => [],
];

if (in_array('logsView', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Transactions Logs',
        'url' => 'main/transactions',
    ];

    $menu[$group]['items'][] = [
        'name' => 'Actions Logs',
        'url' => 'main/logs',
    ];
}

# Admin Tools
$group = 'Admin Tools';
$menu[$group] = [
    'name' => '<i class="fa fa-keyboard-o"></i> ' . $group,
    'items' => [],
];

if (in_array('monitoringView', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Monitoring',
        'url' => 'main/monitoring',
    ];

    $menu[$group]['items'][] = [
        'name' => 'Status',
        'url' => 'main/status',
    ];
}

if (in_array('countriesManage', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Countries',
        'url' => 'countries/index',
    ];
}

if (in_array('providersManage', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Providers',
        'url' => 'providers/index',
    ];
}

if (in_array('operatorsManage', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Operators',
        'url' => 'operators/index',
    ];
}

if (in_array('usersManage', $permissions)) {
    $menu[$group]['items'][] = [
        'name' => 'Users',
        'url' => 'users/index',
    ];

    $menu[$group]['items'][] = [
        'name' => 'Instances',
        'url' => 'instances/index',
    ];
}

function drawItem($item, $path)
{
    if ($path == '') {
        $path = '/';
    }

    $pathNow = str_replace(
        '/index',
        '',
        $item['url']
    );

    echo Html::tag(
        'li',
        Html::a(
            $item['name'],
            Url::toRoute($item['url'])
        ),
        [
            'class' => $pathNow !== $path ?: 'active',
        ]
    );
}

function drawSub($menu, $path)
{
    $urls = [];
    foreach ($menu['items'] as $item) {
        $urls[] = $item['url'];

        // actions
        if (substr_count($item['url'], '/')) {
            $urls[] = explode('/', $item['url'], 2)[0];
        }
    }
    ?>
    <li class="<?= !in_array($path, $urls) ?: 'active' ?>">
        <a href="#"><span class="nav-label"><?= $menu['name'] ?></span> <span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php
            foreach ($menu['items'] as $item) {
                drawItem($item, $path);
            }
            ?>
        </ul>
    </li>
    <?php
}

$ex = explode('/', Yii::$app->request->pathInfo, 2);
?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <a class="close-canvas-menu"><i class="fa fa-times"></i></a>
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <a href="/">
                    <img src="<?= Url::toRoute('/img/linkitlogo.png') ?>" border="0"/>
                </a>
            </li>

            <?php
            foreach ($menu as $item) {
                if (!array_key_exists('url', $item)) {
                    if (count($item['items'])) {
                        drawSub($item, $ex[0]);
                    }
                } else {
                    drawItem($item, $ex[0]);
                }
            }
            ?>
        </ul>
    </div>
</nav>
