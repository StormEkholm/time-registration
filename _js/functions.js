var isIE = /*@cc_on!@*/false || !!document.documentMode;

function getProjects(id, callback){
    $.post('/_service/service.php',{action:"getprojects", customerId:id},function(json){

        //Callback
        if (typeof callback == "function") { callback(json); }

    }, "json");
}

function getCustomers(callback){
    $.post('/_service/service.php',{action:"getCustomers"},function(json){

        //Callback
        if (typeof callback == "function") { callback(json); }

    }, "json");
}

function createEntry(customer, project, date, description, hours, billed, callback){
    $.post('/_service/service.php',{action:"createEntry", customer:customer, project:project, date:date, description:description, hours:hours, billed:billed},function(json){

        //Callback
        if (typeof callback == "function") { callback(json); }

    }, "json");
}

function login(username, password, callback){
    $.post('/_service/service.php', {action:"login", username:username, password:password},function(json){
        //Callback
        if (typeof callback == "function") { callback(json); }
    }, "json");
}

/* Toast */
function showToast(content,timeout){
	var idx = 'toast'+Math.floor((Math.random() * 10000000) + 1) + $.now();

	if(!timeout){ var timeout=2500; } //Default timeout
	var markup = '<div id="'+idx+'" class="toast_window">'+content+'</div>';
	$('body').append(markup);
	$('.toast_window').fadeIn().addClass('animated fadeInDown');
	$('.toast_window').css("left", (($(window).width() - $('.toast_window').outerWidth()) / 2) + $(window).scrollLeft() + "px");
	setTimeout(function(){ hideToast(idx); },timeout);
}

function hideToast(idx){
	if (idx!='') {
		$('#'+idx).fadeOut(500).addClass('animated fadeOutUp');
		setTimeout(function(){ $('#'+idx).remove(); },500);
	} else {
		$('.toast_window').fadeOut(500).addClass('animated fadeOutUp');
		setTimeout(function(){ $('.toast_window').remove(); },500);
	}
}

// Function for loading projects
function loadProjects(){
    let val = $("#customer-select").select2('data')[0].element.value;
    if(isNaN(val)){
        val = 0;
    }else{
        clearTimeout(typeTimeout);
        typeTimeout = setTimeout(function(){
            getProjects(val, function(json){
                $("#project-select").empty();
                if(json === undefined || json.length == 0){
                    $("#newProject").text("Ingen projekter, opret nyt");
                }else{
                    $("#newProject").text("");
                    $.each(json, function(key, value){
                        $("#project-select").append('<option value="'+ value.id +'">'+  value.navn +'</option>');
                    });
                }
                $("#project-select").select2({
                    tags: true,
                    selectOnClose: true,
                    allowClear: true
                });
            });
        },500);
    }
}

if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/serviceworker.js').then(function (registration) {
            console.log('Service worker successfully registered on scope', registration.scope);
        }).catch(function (error) {
            console.log('Service worker failed to register', error);
        });
    });
}






// // Install the service worker
// async function installServiceWorkerAsync(){
//     if ('serviceWorker' in navigator) {
//         try {
//             let serviceWorker = await navigator.serviceWorker.register('/serviceworker.js');
//             console.log(`Service worker registered ${serviceWorker}`);
//         } catch (err) {
//             console.error(`Failed to register service worker: ${err}`);
//         }
//     }
// };
