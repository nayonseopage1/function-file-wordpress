<?php get_header(); ?>

		<?php while( have_posts() ) : the_post(); ?>
             
			<div class="banner-wrap">
				<h4 class="projet-post-title"><?php the_title(); ?><br><span class="city-name"><?php the_field('city'); ?></span></h4>
				
			</div>
			
            <div class="container">
				<div class="row">
					<div class="progect-main-area">
						
					
						<div class="project-single-content">			
							<div class="property-flyerpdf-area">
								<div class="property-flyer-pdf">
									<?php $file1 = get_field('leasing_plan_file'); ?>
										  <?php if( $file1 ): ?>
									<h5>Property Flyer for Download</h5>
                                      <div class="pdf-download-area">
										  
										  <div class="pdf-content-area">
											  <a target="_blank" href="<?php echo $file1['url']; ?>"><img src="https://luxurypropertycare.com/wp-content/uploads/2021/09/pdf-img.png"></a>
										  </div>
										  <div class="pdf-content-area"> 
											  <div class="pdf-content-pad">
												  <p><?php echo $file1['filename']; ?></p>
												  
												  <a target="_blank" href="<?php echo $file1['url']; ?>">Download Now</a>
											  </div>
										  </div>
										  
										</div>
									<?php endif; ?>
								</div>
								<div class="property-flyer-pdf">
									 <?php $file2 = get_field('property_flyer_file'); ?>
											<?php if( $file2 ): ?>
									<h5>Leasing Plan File for Download</h5>
                                      <div class="pdf-download-area">
										 
										  <div class="pdf-content-area">
											  <a target="_blank" href="<?php echo $file2['url']; ?>"><img src="https://luxurypropertycare.com/wp-content/uploads/2021/09/pdf-img.png"></a>
										  </div>
										  <div class="pdf-content-area"> 
											  <div class="pdf-content-pad">
												  <p><?php echo $file2['filename']; ?></p>
												  <a target="_blank" href="<?php echo $file2['url']; ?>">Download Now</a>
											  </div>
										  </div>
										  
										</div>	
									<?php endif; ?>
								</div>
							</div>
							
							<div class="property-excerpt">
								<?php
								$property_excerpt = the_field('property_excerpt');
								if( $property_excerpt ): ?>	
									<p><?php the_field('property_excerpt'); ?></p>
								<?php endif; ?>
							</div>
							<div class="google-map">	
								<?php if( the_field('google_map') ): ?>
									<?php the_field('google_map'); ?>
								<?php endif; ?>
<!-- 							<?php if( get_field('latitude') ): ?>
								<iframe src = "https://maps.google.com/maps?q=<?php the_field('latitude'); ?>,<?php the_field('longitude'); ?>&hl=es;z=14&amp;output=embed" style=" width: 100%; height: 400px; margin-top: 10px; margin-bottom: 10px; "></iframe>
								<?php endif; ?> -->
								
							</div>							
							<div class="property-demographics">
								<?php
								$demographics = the_field('demographics');
								if( $property_excerpt ): ?>	
									<p><?php the_field('demographics'); ?></p>
								<?php endif; ?>
							</div>
							
							<div class="property-contact-area">
								<div class="property-contact-content">
									<p>Want some more information about this property? Click below to contact us.</p>
									<a class="property-con-btn" href="https://luxurypropertycare.com/contact/">Contact Us</a> 
								</div>
								<div class="property-contact-content">
									<div class="email-property-pad">
										<a href="mailto:outreach@luxurypropertycare.com">Click Here to Email This Page</a>
									</div>
								</div>
							</div>
							
						</div>
					
						<div class="project-single-content">
							
						<div class="owl-carousel owl-theme">
							<?php 
							$images = get_field('gallery');
							if( $images ): ?>
							 <?php foreach( $images as $image ): ?>
							<div class="item"> <img src="<?php echo esc_url($image['url']); ?>" /></div>
							<?php endforeach; ?>
							<?php endif; ?>
						</div>
							
							<p class="card-text"><?php the_content(); ?></p>

						</div>
						
						</div>					
					</div>
				</div>


		  <?php endwhile; ?>

<?php get_footer(); ?>