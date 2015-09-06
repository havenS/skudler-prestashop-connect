$(function(){

    console.log('la')
    $('select#skudler_site_id').change(function(){
        if($(this).val() != siteId){
            $('#eventsLocked').show();
            $('#eventsSetting').hide();
        }else{
            $('#eventsLocked').hide();
            $('#eventsSetting').show();
        }
    });

});
