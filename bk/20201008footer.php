<footer class="section footer-classic context-dark bg-image" style="background: #2d3246;">
	<div class="container" style="padding: 3em;">
		<div class="row row-30">
		
			<div class="col-12 col-md-6">
		
				<div class="row">
        			<div class="col-6 text-white footer-text">
        				<li style="list-style-type: none;">&copy; 2019-<?php echo(date('Y')); ?> Game Charts
                            <ul>
                                <li style="list-style-type: none; padding-top: 5px;">
                                    <a href="http://gamecharts.local/about">About</a>
                                </li>
                                <li style="list-style-type: none; padding-top: 5px;">
                                    <a href="http://gamecharts.local/privacy">Privacy</a>
                                </li>
                                <li style="list-style-type: none; padding-top: 5px;">
                                    <a href="http://gamecharts.local/cookies">Cookies Policy</a>
                                </li>
                            </ul>
                        </li>
                    </div>
                    <div class="col-6 text-white footer-text">
                        <li style="list-style-type: none;">Supported Platforms
                            <ul style="padding-top: 10px;">
                                <?php foreach($stores as $store):?>
                                <li style="list-style-type: none; padding-top: 5px;"><a class="footer-items" href="http://gamecharts.local/<?php echo $store->Store?>"><?php echo $store->Store?></a>
                                    <?php endforeach;?>
                            </ul>
                        </li>
                    </div>
				</div>
                
                <div class="row">
        			<div class="col-3">
        				<a href="https://www.facebook.com/Gamecharts-111747700514669/"><i class="fab fa-facebook-f"></i> Facebook</a>
        			</div>
        			
        			<div class="col-3">
        				<a href="https://www.instagram.com/gamechartsorg/"><i class="fab fa-instagram"></i> Instagram</a>
        			</div>
        			
        			<div class="col-3">
        				<a href="https://www.youtube.com/channel/UCBXUnqxAMX8NUZvmN6VlROA/"><i class="fab fa-youtube"></i> Youtube</a>
        			</div>
        			
        			<div class="col-3">
        				<a href="https://twitter.com/Gamecharts1"><i class="fab fa-twitter"></i> Twitter</a>
        			</div>
        		</div>

        	</div>
            
            <div class="col-12 col-md-6 ">
            	<div class="row">
                    <?php foreach($stores as $store):?>
                        <div class="footer-item col-md-6 col-6">
                            <li style="list-style-type: none;"><a href="http://gamecharts.local/<?php echo $store->Store?>/top">Top <?php echo $store->Store?> Games</a>
                                <ul style="padding-top: 10px;">
                                    <?php $platform_top_games = get_top_games($store->Store); foreach ($platform_top_games as $platform_top_game):?>
                                        <li style="list-style-type: none; padding-top: 5px;"><a class="footer-items" href="http://gamecharts.local/<?php echo $store->Store?>/<?php echo $platform_top_game->NameSEO?>"><?php echo $platform_top_game->Name?></a></li>
                                    <?php endforeach;?>
                                </ul>
                            </li>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- SCHEMA.ORG -->
<script type="application/ld+json">{"@context":"http://schema.org","@type":"Organization","name":"GAMECHARTS","url":"http://gamecharts.local/","address":"","sameAs":["https://www.facebook.com/Gamecharts-111747700514669/","https://www.youtube.com/channel/UCBXUnqxAMX8NUZvmN6VlROA/","https://twitter.com/Gamecharts1","https://www.instagram.com/gamechartsorg/"]}</script>