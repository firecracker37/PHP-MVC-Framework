<?php
    $menu = Router::getMenu('menu_acl');
    $currentPage = currentPage();
?>
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <a class="navbar-brand" href="#"><?=SITE_BRAND_TEXT?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <ul class="navbar-nav mr-auto">
        <?php foreach($menu as $key => $val):
            $active = '';?>
            <?php if(is_array($val)): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$key?></a>
                    <div class="dropdown-menu" aria-labelledby="dropdown01">
                    <?php foreach($val as $k => $v):
                        $active = ($v == $currentPage)? 'active':''; ?>
                        <?php if($k == 'separator') : ?>
                            <hr role="separator" class="divider" />
                        <?php else: ?>
                            <a class="dropdown-item <?=$active?>" href="<?=$v?>"><?=$k?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
                </li>
                <?php else:
                    $active = ($val == $currentPage)? 'active':''; ?>
                    <li class="nav-item <?=$active?>"><a class="nav-link" href="<?=$val?>"><?=$key?></a></li>
            <?php endif; ?>

        <?php endforeach; ?>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                echo (Session::exists(CURRENT_USER_SESSION_NAME)) ? currentUser()->fname : 'User';
                ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdown02">
                <?php
                if(Session::exists(CURRENT_USER_SESSION_NAME)) {
                    //Create the items for the dropdown menu if the user IS logged in
                    echo '<a class="dropdown-item" href="' . PROOT . 'dashboard/index">Dashboard</a>';
                    echo '<hr role="separator" class="divider" />';
                    echo '<a class="dropdown-item" href="' . PROOT . 'register/logout">Logout</a>';
                } else {
                    //Create the items for the dropdown menu if the user IS NOT logged in
                    echo '<a class="dropdown-item" href="' . PROOT . 'register/login">Login</a>';
                    echo '<a class="dropdown-item" href="' . PROOT . 'register/register">Register</a>';
                }
                ?>
            </div>
      </li>
    </ul>
  </div>
</nav>