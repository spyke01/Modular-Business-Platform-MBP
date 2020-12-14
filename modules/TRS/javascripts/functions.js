$(function() {
  // TinyMCE for report templates
  if ($('textarea.reportTemplate').length) {
    tinymce.init({
      selector: 'textarea.reportTemplate',
      relative_urls: false,
      remove_script_host: false,
      plugins: [
        'advlist autolink link image lists charmap hr anchor spellchecker',
        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        'table directionality emoticons paste',
      ],
      removed_menuitems: 'newdocument',
      //content_css: "css/content.css",
      toolbar: 'insertfile undo redo | styleselect | fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media fullpage | forecolor backcolor emoticons',
      style_formats: [
        { title: 'Bold text', inline: 'b' },
        { title: 'Red text', inline: 'span', styles: { color: '#ff0000' } },
        { title: 'Red header', block: 'h1', styles: { color: '#ff0000' } },
      ],
    });
  }
  /*
  // New form wizard
  if ($("#wizard-demo").length) {
    $.fn.wizard.logging = true;

    var wizard = $("#wizard-demo").wizard({
      showCancel: true,
      isModal: false
    });

    $(".chzn-select").chosen();

    wizard.el.find(".wizard-ns-select").change(function() {
      wizard.el.find(".wizard-ns-detail").show();
    });

    wizard.el.find(".create-server-service-list").change(function() {
      var noOption = $(this).find("option:selected").length == 0;
      wizard.getCard(this).toggleAlert(null, noOption);
    });

    wizard.cards["name"].on("validated", function(card) {
      var hostname = card.el.find("#new-server-fqdn").val();
    });

    wizard.on("submit", function(wizard) {
      var submit = {
        "hostname": $("#new-server-fqdn").val()
      };

      setTimeout(function() {
        wizard.trigger("success");
        wizard.hideButtons();
        wizard._submitting = false;
        wizard.showSubmitCard("success");
        wizard.updateProgressBar(0);
      }, 2000);
    });

    wizard.on("reset", function(wizard) {
      wizard.setSubtitle("");
      wizard.el.find("#new-server-fqdn").val("");
      wizard.el.find("#new-server-name").val("");
    });

    wizard.el.find(".wizard-success .im-done").click(function() {
      wizard.reset().close();
    });

    wizard.el.find(".wizard-success .create-another-server").click(function() {
      wizard.reset();
    });

    $(".wizard-group-list").click(function() {
      alert("Disabled for demo.");
    });

    $("#btnAddNewReport").click(function() {
      wizard.show();
    });

    wizard.show();
  }
  */
});
