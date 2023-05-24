function new_sitterposition() {
    "use strict";
    $('#sitterposition').modal('show');
    $('.edit-title').addClass('hide');
    $('.add-title').removeClass('hide');
    $('#additional').html('');
}
function edit_sitterposition(invoker, id) {
    "use strict";
    $('#additional').append(hidden_input('id', id));
    $('#sitterposition input[name="position_name"]').val($(invoker).data('name'));
    $('#sitterposition textarea[name="position_description"]').val($(invoker).data('position_description'));
    $('#sitterposition').modal('show');
    $('.add-title').addClass('hide');
    $('.edit-title').removeClass('hide');
}
