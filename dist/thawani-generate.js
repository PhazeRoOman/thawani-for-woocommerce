jQuery(document).ready(function ($) {
  let th_generate_btn = $("#thawani-gw-generate");
  let th_copy_btn = $("#thawani-gw-copy");
  let th_generated_link = $("#thawani-gw-generated-link");
  th_generate_btn.on("click", function () {
    const post_id = $("#post_ID").val();
    th_generate_btn.html(" generating .. ");
    $.ajax({
      url: ajaxurl,
      method: "POST",
      data: {
        action: "thawani_gw_get_checkout",
        order_id: post_id,
      },
      success: function (data) {
        th_generate_btn.html("generated");
        th_generate_btn.attr("disabled", true);
        th_copy_btn.attr("disabled", false);
        $('#thawani_session').val(data.session_id);
        th_generated_link.val(data.checkout);

        th_copy_btn.click(function () {
          generated_link_text = document.querySelector(
            "#thawani-gw-generated-link"
          );
          generated_link_text.select();
          document.execCommand("copy");
        });
        console.log(data);
      },
      error: function (xhr) {
        th_generate_btn.html("ops something went wrong");
        console.error(xhr);
      },
    });
  });
});
