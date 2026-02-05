
        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="deznav">
            <div class="deznav-scroll">
                <ul class="metismenu" id="menu">

                <?php
                foreach ($menus as $row) {
                    $menu=$row->menu;
                    $micon=$row->micon;
                    $mid=$row->mid;
                    $submenuflag=$row->submenuflag;
                    ?>

                    <li>
                    <a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="{{$micon}}"></i>
                    <span class="nav-text">{{$menu}}</span>
                    </a>

                        <?php
                        if($submenuflag==1)
                        {
                            ?>
                              <ul aria-expanded="true">
                                <?php
                                    foreach ($row->submenu as $rk) {
                                        $submenu=$rk->submenu;
                                         $submenulink=trim($rk->submenulink);
                                         if($submenulink!="#")
                                         {
                                            $href=route("$submenulink");
                                         }
                                         else{
                                            $href='#';
                                         }

                                         ?>
                                      <li><a href="{{$href}}">{{$submenu}}</a></li>
                                         <?php
                                    }
                                ?>
                              </ul>
                            <?php
                        }
                        ?>

                    </li>
                    <?php
                }
                ?>



                </ul>

                <a href="{{route('appointment')}}">
                    <div class="plus-box">
                        <p>Create new appointment</p>
                    </div>
                </a>
                <div class="copyright">
                    <p><strong>DTMS</strong> © 2022 All Rights Reserved</p>
                    <p>Developed By Netroxe IT Solutions</p>
                </div>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->
        <style>
            [data-sidebar-style="full"][data-layout="vertical"] .menu-toggle .deznav .metismenu li>ul{
                width: 13rem;
            }
            [data-sidebar-style="full"][data-layout="vertical"] .menu-toggle .deznav .metismenu li>ul {
                top:-20px;
            }
            [data-sidebar-style="mini"][data-layout="vertical"] .deznav .metismenu>li:hover>ul {
                top:-20px;
            }
        </style>
