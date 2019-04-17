let $form = $('#complain-form');
if ($form.length > 0) {
  $form.find('.js-btn-save').on('click', function () {
    let self = $(this);
    self.button('loading');
    $.post($form.attr('action'), $form.serialize())
      .success(() => {
        $form.find('.js-review-remind').fadeIn('fast', function () {
          window.location.href = window.location.origin;
        });
      })
      .error((response) => {
        self.button('reset');
      });
  });
}