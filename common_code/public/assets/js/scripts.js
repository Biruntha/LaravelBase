let dataTableObj = null;
var form = null;

$(document).ready(function () {
    //$('.searchable-select').select2();

    $("#page-loader").fadeOut();

    //making the active links highlighted ================================
    let thisUrl = window.location.href.split("?")[0];
    thisUrl = thisUrl.split("#")[0];
    thisUrl = thisUrl.trim();

    if(thisUrl.slice(-1) == "/")
        thisUrl = thisUrl.substring(0, thisUrl.length-1);

    $(".header-icon, .mobile-footer a, .sidebar-default a").removeClass("active");
    $(".header-icon[href='"+ thisUrl +"'], .mobile-footer a[href='"+ thisUrl +"'], .sidebar-default a[href='"+ thisUrl +"']").addClass("active");

    //====================================================================

    //hiding error messages after some time
    setTimeout(() => {
        $(".alert:not(.dont-hide").slideUp();
    }, 12000);

    $("#btn-filter").on("click", function () {
        $('#filter-panel').slideToggle();
    });

    $("#btn-import").on("click", function () {
        $('#import-panel').slideToggle();
    });

    $(".mobile-filter-btn").on("click", function(){
        $('.mobile-filter-overlay').fadeIn();
        $('.mobile-filter').slideDown();
        $("body").css("maxHeight", "100vh").addClass("overflow-hidden");
    });

    $(".mobile-filter .close-btn").on("click", function(){
        $('.mobile-filter-overlay').toggle();
        $('.mobile-filter').slideToggle();
        $("body").css("maxHeight", "unset").removeClass("overflow-hidden");
    });

    $("body").on('click', '.toggle-password', function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#password");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    //filling attributes for td columns in mobile view
    $.each($('#data-table thead tr th'), function(i, th){
        if($(th).text() != "" && $(th).text() != "Actions")
            $('#data-table tbody tr td:nth-child('+ (i+1) +')').attr("label", $(th).text() + ": ");
    });

    //lazy loading images
    console.log($(".lazy-load"));
    $.each($(".lazy-load"), function(ii, img){
        $(img).on("load", function () {
            $(img).parent().find(".placeholder-img").addClass("d-none");
            $(img).removeClass("d-none");
        }).each(function(){
            if(this.complete) {
              $(this).trigger('load');
            }
        });

        $(img).on("error", function () {
            console.error("Cannot load image");
        });
    });

    $(".confirm-action").on("submit", function (e) {
        form = this;
        console.log(form);
        e.preventDefault();
        swal({
            title: "Confirmation",
            text: "Are you sure that you want to proceed?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#E77300",
            buttons: [
                'No',
                'Yes'
            ],
            dangerMode: true,
        }).then(function (result) {
            console.log("actions");
            if (result.value) {
                console.log("ok");
                form.submit();
            } else {
                console.log("no");
                return;
            }
        });
    });

    $(".confirm-action-link").on("click", function (ev) {
        ev.preventDefault();
        var urlToRedirect = ev.currentTarget.getAttribute('href');
        console.log(urlToRedirect);
        swal({
            title: "Confirmation",
            text: "Are you sure that you want to proceed?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#E77300",
            buttons: [
                'No',
                'Yes'
            ],
            dangerMode: true,
        }).then(function (result) {
            if (result.value) {
                // swal("Success", {
                //     icon: "success",
                // });
                window.location.assign(urlToRedirect);
            } else {
                //swal("Your imaginary file is safe!");
            }
        });
    });

    //loaders for form and anchor tag ===========================
    $("form:not(.confirm-action)").on("submit", function(e){
        $("#page-loader").show();

        setTimeout(() => {
            $("#page-loader").hide();
        }, 10000);

        return true;
    });  
    
    $("a:not([target='_blank'], [href=''], [href='#'], [href*='mailto'], [href*='tel'], .confirm-action-link)").on("click", function(e){
        $("#page-loader").show();

        setTimeout(() => {
            $("#page-loader").hide();
        }, 3000);
        
        return true;
    });  
    //===========================================================

    //validating form submission ===============================
    $("form").on("submit", function(e){
        let formFields = $(this).find("input,select,textarea");

        $.each(formFields, function(fi, f){
            if(!validateField(f))
                e.preventDefault();
        });

        return true;
    });  

    /**
    $("form input, form select, form textarea").on("keyup", function(e){
        validateField($(this));
    });

    $("form input, form select, form textarea").on("blur", function(e){
        validateField($(this));
    });**/

    function validateField(f)
    {
        let isValid = true;
        let msg = "";

        let value = ($(f).val() + "").trim();

        
        //=================================================================
        //Required validation 
        //=================================================================
        if($(f).is("[required]"))
        {
            if($(f).is(':checkbox'))
            {
                if(!$(f).prop("checked"))
                {
                    isValid = false;
                    msg = "You need to check this";
                }
            }       
            else if(value == "")
            {
                isValid = false;
                msg = "Required";
            }
        }

        //=================================================================
        //Special validations
        //=================================================================
        if(value != "" && isValid && !$(f).hasClass("igore-sp-validations"))
        {
            if($(f).attr("type") == "password" && value.length < 6)
            {
                isValid = false;
                msg = "Should be atleast 6 characters";
            } 
            else if($(f).attr("type") == "email" && !(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,20})+$/.test(value)))
            {
                isValid = false;
                msg = "Invalid email address";
            }  
            else if($(f).attr("type") == "number")
            {
                let thisVal = parseFloat(value + "");
                let thisMin = parseFloat($(f).attr("min") + "");
                let thisMax = parseFloat($(f).attr("max") + "");

                if(isNaN(thisVal))
                {
                    isValid = false;
                    msg = "Should be a number";
                }
                else if(!isNaN(thisMin) && thisMin > thisVal)
                {
                    isValid = false;
                    msg = "Should be greater than " + thisMin;
                }
                else if(!isNaN(thisMax) && thisMax < thisVal)
                {
                    isValid = false;
                    msg = "Should be lesser than " + thisMax;
                }
            }
            else if($(f).attr("type") == "file")
            {
                let fileSize = f.files[0].size/(1024*1024);
                //console.log("---------------- " + fileSize);
                if(fileSize > 10)
                {
                    isValid = false;
                    msg = "The file should be less than 10 MB";
                }
            }
            else if($(f).hasClass("telephone") && value != "" && !(/^(?:7|0|(?:\+94))[0-9]{7,15}$/.test(value)))
            {
                isValid = false;
                msg = "Invalid telephone number";
            }
        }

        $(f).parent().find(".validation-errors").remove();
        $(f).removeClass("error-input");

        if(!isValid)
        {
            setTimeout(() => {
                $(f).addClass("error-input");
                $(f).parent().append("<label class='validation-errors text-danger fw-normal font-100 font-italic ms-2 d-block'><i class='fas fa-times me-1 mt-1'></i>"+ msg +"</label>");
                //console.log($(f).parent().html());
                $("#page-loader").hide();
                return false;                
            }, 1);
        }
        else 
            return true;
    }
    //==========================================================
});

function buildDataTable(scrollX = true, responsive = false) {
    
    //filling attributes for td columns in mobile view
    $.each($('#data-table thead tr th'), function(i, th){
        if($(th).text() != "" && $(th).text() != "Actions")
            $('#data-table tbody tr td:nth-child('+ (i+1) +')').attr("label", $(th).text() + ": ");
    });

    dataTableObj = $('#data-table').DataTable({
        "lengthMenu": [
            [20, 50, 100, -1],
            [20, 50, 100, "All"]
        ],
        "order": [],
        "language": {
            "info": "Showing _START_ to _END_ of _TOTAL_ records",
            "decimal": ".",
            "thousands": ","
        },
        
        colReorder: true,
        stateSave: true,
        scrollX: scrollX,
        'responsive': responsive,
        "columnDefs": [{
            "orderable": false,
            "targets": ["no-sort"]
        }]
    });

    $('#dlength-sel').on("change", function () {
        $('.dataTables_length select').val($('#dlength-sel').val());
        $('.dataTables_length select').trigger("change");
    });

    $('#txt-search').keyup(function () {
        dataTableObj.search($(this).val()).draw();
    });
}

function addToWishlist(ele, vacancy) {
    if (!$(ele).hasClass("added")) {
        sendHttpRequest("/wishlist", "POST", {
            vacancy: vacancy
        }, function (res) {
            console.log(res);

            if (res.is_success) {
                if (res.data.status) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);

                    $(ele).parent().find(".icon-wlist.added").show();
                    $(ele).hide();
                    //console.log('Hiiiiiiiiiiiiiiiiiiiii');
                    $("#add-to-wishlist").hide();
                    $("#remove-from-wishlist").show();
                } else {
                    $("#failedToast").toast("show");
                    $("#failedToast .toast-body").html(res.data.msg);
                }
            }
        }, isToShowLoader = true);
    } else {
        sendHttpRequest("/wishlist/remove", "POST", {
            vacancy: vacancy
        }, function (res) {
            console.log(res);

            if (res.is_success) {
                if (res.data.status) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    $(ele).parent().find(".icon-wlist.open").show();
                    $(ele).hide();

                    $("#add-to-wishlist").show();
                    $("#remove-from-wishlist").hide();
                } else {
                    $("#failedToast").toast("show");
                    $("#failedToast .toast-body").html(res.data.msg);
                }
            }
        }, isToShowLoader = true);
    }
}

function addToFollowlist(ele, company) {
    if (!$(ele).hasClass("added")) {
        sendHttpRequest("/follow", "POST", {
            company: company
        }, function (res) {
            console.log(res);

            if (res.is_success) {
                if (res.data.status) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);

                    $(ele).parent().find(".icon-wlist.added").show();
                    $(ele).hide();

                    $("#unfollow").show();
                    $("#follow").hide();

                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    $("#failedToast").toast("show");
                    $("#failedToast .toast-body").html(res.data.msg);
                }
            }
        }, isToShowLoader = true);
    } else {
        sendHttpRequest("/unfollow", "POST", {
            company: company
        }, function (res) {
            console.log(res);

            if (res.is_success) {
                if (res.data.status) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    $(ele).parent().find(".icon-wlist.open").show();
                    $(ele).hide();

                    $("#follow").show();
                    $("#unfollow").hide();

                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    $("#failedToast").toast("show");
                    $("#failedToast .toast-body").html(res.data.msg);
                }
            }
        }, isToShowLoader = true);
    }
}

function addToCart(ele, site, isToReloadPage = false) {
    if (!$(ele).hasClass("added")) {
        sendHttpRequest("/cart", "POST", {
            site: site
        }, function (res) {
            console.log(res);

            if (res.is_success) {
                if (res.data.status) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    carts.push(site);
                    $(ele).parent().find(".icon-cart.added").show();
                    $(ele).hide();

                    if(isToReloadPage)
                        location.reload();

                    //facebook pixel event
                    try{
                        let siteObj = siteMap[site];
                        fbq('track', 'AddToCart', {'contentId' : siteObj.code});
                    }
                    catch(err){
                        console.error(err);
                    }

                    try{
                        $(".cart-count").html(carts.length + "");
                    }
                    catch(err){}
                } else {
                    $("#failedToast").toast("show");
                    $("#failedToast .toast-body").html(res.data.msg);
                }
            }
        }, isToShowLoader = true);
    } else {
        sendHttpRequest("/cart/remove", "POST", {
            site: site
        }, function (res) {
            console.log(res);

            if (res.is_success) {
                if (res.data.status) {
                    $("#successToast").toast("show");
                    $("#successToast .toast-body").html(res.data.msg);
                    $(ele).parent().find(".icon-cart.open").show();
                    $(ele).hide();

                    if(isToReloadPage)
                        location.reload();

                    var index = carts.indexOf(site);
                    if (index !== -1) {
                        carts.splice(index, 1);
                    }

                    try{
                        $(".cart-count").html(carts.length + "");
                    }
                    catch(err){}
                } else {
                    $("#failedToast").toast("show");
                    $("#failedToast .toast-body").html(res.data.msg);
                }
            }
        }, isToShowLoader = true);
    }
}

$(".share-link").on("click", function(){
    const link = $(this).attr("data-link");
    const title = $(this).attr("data-title");

    try
    {
        let isMobile = $(window).width() < 991;

        if (isMobile && navigator.share) {
            navigator.share({
            title: "Placements LK",
            text: title,
            url: link,
            })
            //.then(() => console.log('Successful share'))
            //.catch((error) => console.log('Error sharing', error));
        }
        else
        {
            shareLink(title, link);
        }    
    }
    catch(err)
    {
        shareLink(title, link);
    }
});

$("#shareModal a").on("click", function(){
    $("#shareModal").modal("hide");
});

function shareLink(title, link)
{
    let twitterLink = `https://twitter.com/intent/tweet?url=${link}&text=${title}&hashtags=placementslk`;
    $("#link-twitter").attr("href", twitterLink);

    let facebookLink = `http://www.facebook.com/sharer/sharer.php?u=${link}`;
    $("#link-facebook").attr("href", facebookLink);

    let whatsAppLink = `https://api.whatsapp.com/send?text=${link}`;
    $("#link-whatsapp").attr("href", whatsAppLink);

    let linkedInLink = `https://www.linkedin.com/sharing/share-offsite/?url=${link}`;
    $("#link-linkedin").attr("href", linkedInLink);

    let viberLink = `viber://forward?text=${link}`;
    $("#link-viber").attr("href", viberLink);

    let telegramLink = `https://telegram.me/share/url?url=${link}`;
    $("#link-telegram").attr("href", telegramLink);

    let emailLink = `mailto:?body=${link}`;
    $("#link-email").attr("href", emailLink);
    
    $("#link-copy").val(link);

    $("#shareModal").modal("show");
}

function copyText(eleId)
{
    var copyText = document.getElementById(eleId);

    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */

    /* Copy the text inside the text field */
    navigator.clipboard.writeText(copyText.value);

    $("#successToast").toast("show");
    $("#successToast .toast-body").html("Link copied");

    setTimeout(() => {
        $("#successToast").toast("hide");
    }, 3000);
}

playPlaceholderAnimation($(".placeholder-animation"), 
    [
        {type : "Field", value: "Information Technology"},
        {type : "Position", value: "Software Engineer"},
        {type : "Company", value: "XYZ (Pvt) Ltd."},           
        {type : "District/Region", value: "Colombo"},
    ]
);
var currentWordIndex = -1;
var currentCharIndex = -1;

function playPlaceholderAnimation(ele, textArray)
{
    //console.log("inside playPlaceholderAnimation");
    setInterval(() => {
        let currentWord = currentWordIndex == -1 ? null : textArray[currentWordIndex];
        if(currentWord == null || currentCharIndex == currentWord.value.length-1){ //next word
            currentWordIndex = currentWordIndex == textArray.length-1 ? 0 : currentWordIndex+1;
            currentCharIndex = -1;
            //console.log("currentWordIndex: " + currentWordIndex);
            currentWord = textArray[currentWordIndex];
        }
        else{ //next char
            currentCharIndex++;            
        }
        
        //console.log("currentWord: " + currentWord + " | currentCharIndex: " + currentCharIndex);
        let wordPart = currentWord.value.substring(0, currentCharIndex+1);
        ele.attr("placeholder", "Search by " + currentWord.type + " - " + wordPart); 
    }, 200);
    
}


//=====================================================
// OBJECT BROWSER

//$(".searchable-select").attr("disabled", "disabled");
$(".searchable-select").parent().css("position", "relative");

$.each($(".searchable-select"), function(si, s){
    let title = $(s).parent().find("label").text();
    let apiUrl = $(s).attr("data-api") || "";
    $(s).parent().append("<div class='searchable-select-overlay input-overlay' data-title='"+title+"' data-type='"+ $(this).attr("data-type") +"' data-target='"+$(this).attr("id")+"' data-api='"+apiUrl+"'></div>")
});

$(".searchable-select-overlay").on("click", function(e){
    let targetId = $(this).attr("data-target");
    let isMultiple = $(this).attr("data-type") == "multiple";
    let title = $(this).attr("data-title") || "Item";
    let apiUrl = $(this).attr("data-api") || "";

    e.preventDefault();
    this.blur();
    window.focus();

    let itemList = [];
    $.each($("#" + targetId).find("option"), function(oi, o){
        itemList.push({text: $(o).html(), value: $(o).attr("value")});
    });

    //populating the pre-selected values
    let defaultValues = [];

    if(isMultiple)
        defaultValues = $("#" + targetId).val();
    else
        defaultValues.push($("#" + targetId).val());

    initializeBrowser(title, itemList, defaultValues, true, targetId, isMultiple, apiUrl);
});

$("#search-objects").on("keyup", function(){
    let srch = $(this).val().toLowerCase().trim();
    let numberOfItemsFiltered = 0;
    $(".new-object-item, .no-object-item, .object-item-alert").remove();

    $.each($("#objectBrowserModal .object-item"), function(oi, o){
        let thisText = $(o).attr("data-text").toLowerCase().trim();

        if(thisText.indexOf(srch) >= 0){
            numberOfItemsFiltered++;
            $(o).show();
        }
        else
            $(o).hide();
    });

    if(numberOfItemsFiltered == 0 && $("#objectBrowserModal .object-items").attr("data-api") == "")
    {
        let htmlStr = `<div class="no-object-item mb-2 p-2 py-3 text-center bg-white rounded ">
                         <i>Sorry, no results found</i> </div>`;
        $("#objectBrowserModal .object-items").append(htmlStr)
    }

    if(srch!="" && $("#objectBrowserModal .object-items").attr("data-api"))
    {
        let htmlStr = `<div class="no-object-item mb-2 p-2 py-3 text-center bg-white rounded ">
                        <input type='button' class='btn btn-success me-2' onclick='saveObjectItem()' value='Save and Select ${$(this).val().trim()}' />
                        </div>`;
        $("#objectBrowserModal .object-items").append(htmlStr)
    }   
});

function initializeBrowser(title, items, defaultValues, isSingleSelect, sourceElementId, isMultiple=false, apiUrl="")
{
    $("#search-objects").val("");
    $("#objectBrowserModal .object-items").html("");
    $("#objectBrowserModal .modal-title").html("Select " + title);
    $("#objectBrowserModal .modal-title").attr("data-type " + title);
    $("#search-objects").attr("placeholder", "Search... ");

    $("#objectBrowserModal .object-items").attr("data-source", sourceElementId);
    $("#objectBrowserModal .object-items").attr("data-api", apiUrl);
    $("#objectBrowserModal .object-items").attr("data-type", (isMultiple ? "multiple" : "single"));

    $.each(items, function(ii, i){
        let htmlStr = `<div class="object-item mb-2 p-1 bg-white rounded shadow-md app-card app-card2 ${defaultValues.includes(i.value) ? "selected" : ""}" data-text="${i.text}" data-value="${i.value}" data-source="${sourceElementId}">
                            <div class="d-flex" style="position:relative">
                                <div class="details p-2">
                                    <p class="display-text"><i class="fas fa-check text-light tick me-2"></i> ${i.text}</p>
                                </div>
                            </div>
                        </div>`;
        $("#objectBrowserModal .object-items").append(htmlStr);
    });

    $("#objectBrowserModal .object-item").unbind("click");
    $("#objectBrowserModal .object-item").on("click", function(){
        let source = $(this).attr("data-source");
        let value = $(this).attr("data-value").trim();

        if(isMultiple)
        {
            let values = $("#" + source).val();

            if(!$(this).hasClass("selected"))
            {
                values.push(value);
            }
            else
            {
                const index = values.indexOf(value);
                if (index > -1) {
                    values.splice(index, 1);
                }
            }
            
            $("#" + source).val(values);
            $(this).toggleClass("selected");
            $("#" + source).trigger("change");
        }
        else
        {
            $("#" + source).val(value);
            $("#" + source).trigger("change");
            $("#objectBrowserModal").modal("hide");
        }
        
    });

    $("#objectBrowserModal").modal("show");
}

function saveObjectItem()
{
    $(".object-item-alert").remove();
    let srch = $("#search-objects").val().trim();

    if(!srch)
        return false;

    srch = srch[0].toUpperCase() + srch.slice(1);

    let data = {
        name: srch
    }

    sendHttpRequest($("#objectBrowserModal .object-items").attr("data-api"), "POST", data, function (res) {
        console.log(res);
        if (res.is_success) {
            if (res.data.status) {
                let sourceElementId = $("#objectBrowserModal .object-items").attr("data-source");
                $("#" + sourceElementId).prepend(`<option value='${res.data.data.id}'>${res.data.data.name}</option>`)
                
                if($("#objectBrowserModal .object-items").attr("data-type") == "multiple")
                {
                    let selectedValues = $('#' + sourceElementId).val();
                    selectedValues.push(res.data.data.id);
                    $('#' + sourceElementId).val(selectedValues)
                    $('#' + sourceElementId).trigger('change');
                    $('#' + sourceElementId).parent().find(".searchable-select-overlay").trigger('click');
                }
                else
                {
                    $('#' + sourceElementId).val(res.data.data.id);
                    $("#objectBrowserModal").modal("hide");
                }


                //Needs to be defined in the blade as a function
                if(ObjectItemCallback)
                    ObjectItemCallback(sourceElementId, res.data.data)

            } else {
                let htmlStr = `<div class='alert alert-danger object-item-alert'>${res.data.msg}</div>`;
                $("#objectBrowserModal .object-items").prepend(htmlStr);
            }
        }else {
            let htmlStr = `<div class='alert alert-danger object-item-alert'>Sorry, we couldn't complete the operation. Please try again.</div>`;
            $("#objectBrowserModal .object-items").prepend(htmlStr);
        }
    }, isToShowLoader = true);
}
//=====================================================
//Time Remaining COunter
var now = null;

setInterval(() => {
    showTimeCounters(); 
    //console.clear()

    if(now) 
        now.setSeconds(now.getSeconds() + 1);
}, 1000);



function showTimeCounters()
{
    $.each($(".show-timer"), function(ti, t){
        var textToShow = "";

        try
        {
            var dateFrom = $(t).attr("data-from");
            dateFrom = strToDate(dateFrom);
            
            var dateTo = $(t).attr("data-to");
            dateTo = strToDate(dateTo);

            if(now == null)
                now = strToDate($(t).attr("data-now"));

            var timerFrom = null;
            var timerTo = null;
            var fontText = 150;
            var fontLabel = 80;

            if($(t).attr("data-font") == "big")
            {
                fontText = 200;
                fontLabel = 100;
            }
    
            if(dateFrom > now && dateTo > dateFrom)
                textToShow = getTimeDifference(now, dateFrom, fontText, fontLabel) + "";
            else if(dateTo > now)
                textToShow = getTimeDifference(now, dateTo, fontText, fontLabel) + "";
            else
                textToShow = "<div class='mt-2 d-flex'>" + wrapTimerText("<span class='text-danger'>EXPIRED</span>", "The career Fair has been closed", fontText, fontLabel) + "</div>";

        }
        catch(err)
        {
            textToShow = $(t).attr("date-from");
        }

        $(t).html(textToShow);
    });
}

function strToDate(str)
{
    if(!str || str.split(" ").length != 2)
        return new Date();

    try{
        let datePart = str.split(" ")[0];
        let timePart = str.split(" ")[1];
    
        let dArray = datePart.split("-");
        let tArray = timePart.split(":");

        let year = parseInt(dArray[0] + "");
        let month = parseInt(dArray[1] + "") -1;
        let day = parseInt(dArray[2] + "");

        let hour = parseInt(tArray[0] + "");
        let minute = parseInt(tArray[1] + "");
        let second = parseInt(tArray[2] + "");

        return new Date(year, month, day, hour, minute, second);
    }
    catch(err)
    {
        return new Date();
    }
}

function getTimeDifference(timerFrom, timerTo, fontText, fontLabel)
{
    // get total seconds between the times
    var delta = (timerTo - timerFrom) / 1000;
    //console.log(delta);

    // calculate (and subtract) whole days
    var days = Math.floor(delta / 86400);
    delta -= days * 86400;
    days = days > 0 ?  wrapTimerText(days, "DAY", fontText, fontLabel) : "";
    
    // calculate (and subtract) whole hours
    var hours = Math.floor(delta / 3600) % 24;
    delta -= hours * 3600;
    hours = (hours+"").length == 1 ? "0"+hours : hours;
    hours = wrapTimerText(hours, "HR", fontText, fontLabel);
    
    // calculate (and subtract) whole minutes
    var minutes = Math.floor(delta / 60) % 60;
    delta -= minutes * 60;
    minutes = (minutes+"").length == 1 ? "0"+minutes : minutes;
    minutes = wrapTimerText(minutes, "MIN", fontText, fontLabel);
    
    // what's left is seconds
    var seconds = delta % 60;
    seconds = parseInt(seconds + "");
    seconds = (seconds+"").length == 1 ? "0"+seconds : seconds;
    seconds = wrapTimerText(seconds, "SEC", fontText, fontLabel);

    return "<div class='d-flex mt-2'>" + days + hours + minutes + seconds + "</div>";
}

function wrapTimerText(text, label, fontText, fontLabel)
{
    return "<div class='bg-light text-center p-1 w-100 mx-2 rounded'><strong class='font-"+fontText+" mb-0 text-info d-block'>" + text + "</strong><span class='font-"+fontLabel+" fw-bold d-block'>"+ label +"</span></div>";
}

//================================================================================
function resetSingleFilter(eleId, formId)
{
    $("#" + eleId).val("");

    if(formId)
        $("#" + formId).trigger("submit");
}