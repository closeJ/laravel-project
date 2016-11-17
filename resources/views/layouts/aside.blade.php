<div class="navbar navbar-twitch navbar-purple" role="navigation">
        <div class="container">
              <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <span class="small-nav"> <span class="logo">  </span></span>
                    <span class="full-nav"> < Menu > <hr><br></span>
                </a>
               </div>
            <div class="navigation" id="accordion">
                <ul class="nav navbar-nav">
                <?php
                    $i = 0;
                    $j = 10;
                ?>
                @foreach($mainMenuArray as $mainMenu)
                <?php $i++;?>
                    <li>
                        <a href="#">
                            <a href="#collaspe{{ $i }}" data-toggle="collapse" data-parent="#accordion">
                            <span class="full-nav"> {{ $mainMenu['name'] }}&nbsp;&nbsp;&nbsp;
                            @if(isset($subMenuArray[$mainMenu['id']]))
                            <span class="fa fa-caret-down"></span>
                            @endif
                            </span>
                            </a>
                        </a>
                        @if(isset($subMenuArray[$mainMenu['id']]))
                        <ul id="collaspe{{ $i }}" class="panel-collapse collapse">
                            @foreach($subMenuArray[$mainMenu['id']] as $subMenu)
                            <?php $j++; ?>
                            <li>
                                <a style="display:inline-block;" href="{{ url($subMenu['route_name']) }}"><span class="fa fa-long-arrow-right full-nav"> {{ $subMenu['name'] }}
                                @if(isset($subMenuArray[$subMenu['id']]))
                                <a href="#collaspe{{ $j }}" data-toggle="collapse" data-parent="#accordion">
                                <span class="fa fa-caret-down"></span>
                                </a>
                                @endif
                                </span></a>
                                @if(isset($subMenuArray[$subMenu['id']]))
                                    <ul id="collaspe{{ $j }}" class="panel-collapse collapse">
                                        @foreach($subMenuArray[$subMenu['id']] as $subMenu2)
                                        <li class="submenu">
                                            <a href="{{ url($subMenu2['route_name']) }}"> {{ $subMenu2['name'] }}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                @endforeach
                </ul>
            </div><!--/.nav-collapse -->
        </div>
</div>
</div>
<button type="button" class="btn btn-purple btn-md navbar-twitch-toggle" data-toggle="tooltip" data-placement="right" title="Menu">
    <span class="fa fa-bars nav-open"></span>
    <span class="fa fa-times nav-close"></span>
</button>
