<?php
	$user = \Yii::$app->getUser()->getIdentity();
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
			
            <div class="pull-left image" style="min-height:40px;">
				<?php if($user->image){?>
                <img src="<?=$user->image?>" class="img-circle" alt="User Image"/>
				<?php }?>
            </div>
			
            <div class="pull-left info">
				
                <p><?=$user->username?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <!--form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form-->
        <!-- /.search form -->
		<?php
			$items = [];
			$items[] = ['label' => 'Меню', 'options' => ['class' => 'header']];
			
			$menus = [
				'users/usercontroller/view' => [
					'label'=>'Пользователи',
					'icon' => 'fa fa-user', 
					'url' => '/users'
				],
				'rbac/roles/index' => [
					'label'=>'Группы пользователей',
					'icon' => 'fa fa-users', 
					'url' => '/rbac'
				],
			];
			
			foreach($menus as $permName => $menuPoint){
				if(\yii::$app->getUser()->can($permName)){
					$items[] = $menuPoint;
				}
			}

		?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $items/*[
                    
                   /* ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Same tools',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'fa fa-circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'fa fa-circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],*/
            ]
        ) ?>

    </section>

</aside>
