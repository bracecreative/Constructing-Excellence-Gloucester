<?php if(!is_user_logged_in()) : ?>
<div class="text-center">
  <h1 class="heading mt-5">
    Not already a member?
  </h1>
  <div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
      <p style="font-size: 18px; color: #63727a;">Numbers are increasing as a result of the Club’s welcoming atmosphere and dedication to creating an environment where appropriate and leading edge subject matters are addressed. Your views about your industry are important – come and meet like-minded people and help to shape the future.</p>
    </div>
  </div>
  <a href="<?php echo esc_url(home_url('/membership/')); ?>" class="btn orange-rounded">Memberships</a>
</div>
<?php endif; ?>