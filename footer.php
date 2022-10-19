<section class="sponsors">
    <div class="container">
      <div class="row">
        <div class="header mr-auto">
          <h3 class="heading">
            Our Sponsors
          </h3>
        </div>
      </div>

      <div class="logos">
        <div class="row">
          <div class="col-md-2 my-auto text-center p-3">
            <a href="https://www.quattrodesign.co.uk/" target="_blank">
              <img src="<?php echo get_template_directory_uri(); ?>/img/quattro.png" alt="" class="img-fluid">
            </a>
          </div>
          <div class="col-md-2 my-auto text-center p-3">
            <a href="http://www.kier.co.uk/" target="_blank">
              <img src="<?php echo get_template_directory_uri(); ?>/img/kier-logo.png" alt="" class="img-fluid">
            </a>
          </div>
          <div class="col-md-2 my-auto text-center p-3">
            <a href="https://www.egcarter.co.uk/" target="_blank">
              <img src="<?php echo get_template_directory_uri(); ?>/img/carter.jpg" alt="" class="img-fluid">
            </a>
          </div>
          <div class="col-md-2 my-auto text-center p-3">
            <a href="https://rappor.co.uk/" target="_blank">
              <img src="<?php echo get_template_directory_uri(); ?>/img/rappor.png" alt="" class="img-fluid">
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <h4>Contact Us</h4>
          <p>BPE Solicitors LLP
            St James House<br>
            St James Square<br>
            Cheltenham<br>
            GL50 3PR
          </p>

          <p>
            <a href="mailto: hello@ceglos.org.uk">hello@ceglos.org.uk</a>
          </p>
        </div>
        <div class="col-md-3">
          <h4>Site Map</h4>
          <ul>
            <li>
              <a href="<?php echo esc_url(home_url('/events')); ?>">Events</a>
            </li>
            <li>
              <a href="<?php echo esc_url(home_url('/membership')); ?>">Membership</a>
            </li>
            <li>
              <a href="<?php echo esc_url(home_url('/case_studies')); ?>">Case Studies</a>
            </li>
            <li>
              <a href="<?php echo esc_url(home_url('/resources')); ?>">Resource Hub</a>
            </li>
            <li>
              <a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a>
            </li>
          </ul>
        </div>
        <div class="col-md-3 offset-md-3">
          <h4>Connect With Us</h4>
          <a href="https://twitter.com/CE_Glos" target="_blank">
            <i class="fab fa-twitter"></i>
          </a>
          <a href="https://www.instagram.com/ceglos1/" target="_blank">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://www.linkedin.com/company/constructing-excellence-gloucestershire-club/" target="_blank">
            <i class="fab fa-linkedin-in"></i>
          </a>
          <a href="https://www.facebook.com/Constructing-Excellence-Gloucestershire-Club-616672692110900/" target="_blank">
            <i class="fab fa-facebook-f"></i>
          </a>
        </div>
      </div>
      <hr>
      <div class="row">
        <div class="col-md-12">
          <div class="privacy-footer">
            <p>
              <a href="<?php echo esc_url(home_url('/cookie-policy')); ?>">Cookie Policy</a> |
              <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">Privacy Policy</a> | Website Built by
              <a href="https://www.brace.co.uk">Brace Creative Agency</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <?php wp_footer(); ?>
  </div>
</body>
</html>