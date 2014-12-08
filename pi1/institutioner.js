$(document).ready(function () {
    //console.log('1'+$('#content_sidebar_wrapper').length);
    //lib.rightContent.stdWrap.outerWrap = <div id="content_sidebar_wrapper" class="grid-8 omega"><div id="content_sidebar">|</div></div>
    /*$('.sortingColumn').click(function () {
        var theClass = this.className;  // "this" is the element clicked
        var classArray = theClass.split(' ');
        var className = classArray[1].replace('_field','');
        
        console.log(className);
        if(theClass.indexOf('name_sort_field') > 0) {
            $('.staff_category_header').hide();
        } else {
            $('.staff_category_header').show();
        }
        if(classArray[2]=='ascending') {
            $('img', this).attr('src', '/fileadmin/templates/images/shortcuts-down-bronze.png');
        } else {
            $('img', this).attr('src', '/fileadmin/templates/images/shortcuts-up-bronze.png');
        }
        
        $('.'+className).sortElements(function(a, b){
            if(classArray[2]=='ascending') {
                return $(a).text() < $(b).text() ? 1 : -1;
            } else {
                return $(a).text() > $(b).text() ? 1 : -1;
            }
        });
        $( this ).toggleClass( "ascending" );
        $( this ).toggleClass( "descending" );
        
       return false;
    });*/
    $('.sortingColumn').click(function () {
        var theClass = this.className;  // "this" is the element clicked
        var classArray = theClass.split(' ');
        var className = classArray[1].replace('_field','');
        
        if(classArray[2]=='asc') {
            $('img', this).attr('src', '/fileadmin/templates/images/shortcuts-down-bronze.png');
        } else {
            $('img', this).attr('src', '/fileadmin/templates/images/shortcuts-up-bronze.png');
        }
        
        if(theClass.indexOf('name_sort') > 0) {
            //console.log(className+classArray[2]);
            $('.staff_category').hide();
            $('.people').tsort('span.name_sort',{order:classArray[2]});
        } else {
            
            $('.people').tsort('span.category_sort',{order:classArray[2]},'span.name_sort',{order:'asc'});
            $('.staff_category').first().show();
        }
        
        
        $( this ).toggleClass( "asc" );
        $( this ).toggleClass( "desc" );
        
        return false;
    });
    
    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });
});

var xmlHttp;
var hashIndex = 0;
var timerCookie;
var myArray = [];
presIndex=0;
window.onload=function () {
    onLoad();
};
window.onunload=function () {
    onUnload();
};

function korning(scope,action,query,lang,hide_search,html_template,imagefolder,addpeople,removepeople,categories,queryfilter,pluginid,issiteadmin)
{
    $.ajax({
        type : "POST",
        url : 'index.php',
        data: {
            eID : 'tx_institutioner_pi1',
            scope : scope,
            action : action,
            query : query,
            lang : lang,
            hide_search : hide_search,
            html_template : html_template,
            imagefolder : imagefolder,
            addpeople : addpeople,
            removepeople : removepeople,
            categories : categories,
            queryfilter : queryfilter,
            pluginid : pluginid,
            issiteadmin : issiteadmin,
            sid : Math.random(),
        },
        //contentType: "application/json; charset=utf-8",
        dataType: "json",
        beforeSend: function () {
            $('#txtContent').html('<img src="/fileadmin/templates/images/ajax-loader.gif" />');
        },
        success: function(data) {
            if(data) {
                if(action==='exportChoice') {
                    $('.sortrow').hide();
                    $('#hits').hide();
                } else {
                    $('.sortrow').show();
                    $('#hits').show();
                }
                if(action==='editForm') {
                    $('#txtLoginform').html(data.content);
                } else if(action==='saveEditForm') {
                    if(data.content==='ok') {
                        $('#txtLoginform').html('<h1>Data saved successfully</h1><a href="#">Back?</a>');
                    } else {
                        $('#txtLoginform').html('<h1>An error occured</h1><a href="#">Try again?</a>');
                    }
                } else {
                    $('#txtContent').html(data.content);
                }
                if(data.hits) {
                    $('#hits').html(inst_lang_resources.result + ': '+ data.hits + ' '+ inst_lang_resources.hits);
                }

                if(data.facets) {
                    if($('#content_sidebar_wrapper').length===0) {
                        $('#page_title').remove();
                        $('#text_content_main').attr('class', '');
                        $('#text_wrapper').attr('class', 'grid-15');
                        $('#text_wrapper').after('<div id="content_sidebar_wrapper" class="grid-8 omega"><div id="content_sidebar"></div>');
                    }
                    $('#content_sidebar_wrapper').append(data.facets);
                }
                
                if(data.searchbox) {
                    if($('#content_sidebar_wrapper').length===0) {
                        $('#page_title').remove();
                        $('#text_content_main').attr('class', '');
                        $('#text_wrapper').attr('class', 'grid-15');
                        $('#text_wrapper').after('<div id="content_sidebar_wrapper" class="grid-8 omega"><div id="content_sidebar"></div>');
                    }
                    $('#content_sidebar_wrapper').append(data.searchbox);
                }
                
                if(data.export) {
                    if($('#content_sidebar_wrapper').length===0) {
                        $('#page_title').remove();
                        $('#text_content_main').attr('class', '');
                        $('#text_wrapper').attr('class', 'grid-15');
                        $('#text_wrapper').after('<div id="content_sidebar_wrapper" class="grid-8 omega"><div id="content_sidebar"></div>');
                    }
                    $('#content_sidebar_wrapper').append(data.export);
                } 
            }		
        },
        complete: function(data) {
            if(action==='editForm') {
                initAjaxUpload();
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
}

function changeFilter(scope,action,query,lang,hide_search,html_template,imagefolder,addpeople,removepeople,categories)
{
    //console.log(filtertype+'.'+filtervalue);
   /* var tmpValue = '';
    if($.cookie('filter')!=='null' && $.cookie('filter')!=='') {
        tmpValue = $.cookie('filter') + ',';
    }
    tmpValue += filterType + '.' + filterValue;
    $.cookie('filter', tmpValue);*/
    query = $('#tx_institutioner_searchbox').val();

    var queryfilter = new Array();
    var i = 0;
    $('.filterbox input').each(function() {
        if($(this).prop('checked')) {
            queryfilter[i] = $(this).attr('name') + '.' + $(this).val();
            i++;
        }
    });
    lista(scope,action,query,lang,hide_search,html_template,imagefolder,addpeople,removepeople,categories,JSON.stringify(queryfilter));
}

function clearFilter(filter,value)
{
    document.getElementById(filter).selectedIndex = 0;
    var tmpFilter = $.cookie("filter");
    var filterArray = tmpFilter.split(',');
    filterArray = removeByValue(filterArray,filter+'.'+value);
   
    $.cookie('filter', filterArray.join(','));
    solr(null,null,null,'solr');
}

function onLoad()
{
    // this doesn't help, Opera is currently broken:
    if (window.opera && window.history) {
        history.navigationMode = 'compatible';
    }
    // Set-up the polling:
    timerCookie = window.setTimeout(onTick, 200);
}

function onTick()
{
    timerCookie = null;
    myVar = document.location.hash.replace("#","");
    timerCookie = window.setTimeout(onTick, 200);
    // && presIndex != myVar
    if(hashIndex-myVar > 1) {
        var korArray = myArray[myVar].split(';');
        korning(korArray[0],korArray[1],korArray[2],korArray[3],korArray[4],korArray[5],korArray[6],korArray[7],korArray[8],korArray[9],korArray[10],korArray[11],korArray[12]);
        //presIndex = myVar;
        hashIndex--;
    }
}
    
function onUnload()
{
    if (timerCookie) {
        window.clearTimeout(timerCookie);
    }
}

function onFrameLoaded(hash)
{
    location.hash = hash;
}

function lista(scope,action,query,lang,hide_search,html_template,imagefolder,addpeople,removepeople,categories,queryfilter)
{
    pluginid = $('#pluginid').val();
    issiteadmin = $('#issiteadmin').val();

    document.getElementById("txtContent").innerHTML = '<img src="/typo3conf/ext/institutioner/pi1/graphics/loading3.gif" border="0" />';
    
    myArray[hashIndex] = scope + ';' + action + ';' +  query + ';' + lang + ';' + hide_search + ';' + html_template + ';' + imagefolder + ';' + addpeople + ';' + removepeople + ';' + categories + ';' + queryfilter + ';' + pluginid + ';' + issiteadmin;
    
    korning(scope,action,query,lang,hide_search,html_template,imagefolder,addpeople,removepeople,categories,queryfilter,pluginid,issiteadmin);
    
    if(navigator.userAgent.indexOf('MSIE') >= 0 && navigator.userAgent.indexOf('MSIE 8') < 0) {
        window.location.hash = hashIndex;		
        var doc = document.getElementById("historyFrame").contentWindow.document;
        doc.open("javascript:'<html></html>'");
        doc.write("<html><head><script type=\"text/javascript\">parent.onFrameLoaded("+ (hashIndex++) + ");</script></head><body></body></html>");
        doc.close();
    } else {
        window.location.hash = hashIndex++;
    }
}

function initAjaxUpload()
{
    var ul = $('#upload ul');
    
    $('#drop a').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        
        $(this).parent().find('input').click();
    });

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({
        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {
            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name).append('<i>' + formatFileSize(data.files[0].size) + '</i>');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
            }
        },
        
        done: function(e, data){
            $('#uploadimage').html('<img style="width:auto;height:200px;" src="'+$('#imagefolder').val() + data.files[0].name+'" />');
            $('#image').val(data.files[0].name);
        },

        fail:function(e, data){
            // Something has gone wrong!
            console.log(e);
            data.context.addClass('error');
        }

    });
}

// Helper function that formats the file sizes
function formatFileSize(bytes)
{
    if (typeof bytes !== 'number') {
        return '';
    }

    if (bytes >= 1000000000) {
        return (bytes / 1000000000).toFixed(2) + ' GB';
    }

    if (bytes >= 1000000) {
        return (bytes / 1000000).toFixed(2) + ' MB';
    }

    return (bytes / 1000).toFixed(2) + ' KB';
}

function saveEditForm(uid,pid,lang)
{
    var description = $('#description').val();
    var image = $('#image').val();
    if(!description) {
        //error_stuff
    } else {
        lista('','saveEditForm',JSON.stringify({"description":description,"uid":uid,"pid":pid,"lang":lang,"image":image}));
    }
}

function institution_ajaxfelogin(obj)
{
    var prefix=obj.form.id.substr(0,obj.form.id.length-5);
    var scope = $('#user').val();
    $('#'+prefix+'_indication').css('display','block');
    $.ajax({  
        type: 'POST',  
        url: obj.form.action,  
        data: $.param($(obj.form).serializeArray())+'&'+obj.name+'=1&tx_felogin_pi1[ajax]='+prefix,  
        success: function(data) {
            //console.log(data);
            var response = $.parseJSON(data);
            if(response.redirect){
                //window.location.href=response.redirect;
                return false;
            } else {
                if(response.data.indexOf('<!-- ###LOGIN_FORM### -->') > 0) {
                    var responseArray = response.data.split('<!-- ###LOGIN_FORM### -->');
                    $('#loginMsg').html(responseArray[0]);
                } else {
                    var jsonCode = $('#serializevalues').val();
                    var jsonData = $.parseJSON(jsonCode);
                    var editForm = "<p><a href=\"#\" onclick=\"lista('"+scope+"','editForm','','"+jsonData.lang+"','','','"+jsonData.imagefolder+"','','','','','');return false;\">Load editform</a></p>";
                    $('#'+prefix).html(response.data+editForm);
                }
                $('#'+prefix+'_indication').css('display','none');
            }
        }  
    });  
    return false;
}

function showHideLoginform()
{
    $('.sortrow').toggle();
    $('#hits').toggle();
    $('.filterbox').toggle();
    $('#tx_institutioner_searchbox_container').toggle();
    $('#exportMenu').toggle();
    
    $('#txtContent').toggle();
    $('#txtLoginform').toggle();
    $('html, body').animate({ scrollTop: 0 }, 'fast');
}

function exportChoice(scope,action,query,imageSokvag,lang,hide_search,html_template,imagefolder,addpeople,removepeople,categories,queryfilter,pluginid,issiteadmin)
{
    if(!action) return false;
    queryfilter = new Array();
    document.getElementById("exportMenu").options[0].selected = true;
    var actionArray = action.split(";");
    query = $('#tx_institutioner_searchbox').val();
    var i = 0;
    $('.filterbox input').each(function() {
        if($(this).prop('checked')) {
            queryfilter[i] = $(this).attr('name') + '.' + $(this).val();
            i++;
        }
    });
    switch (actionArray[0]) {
        case 'telephoneList':
            var url="index.php?eID=tx_institutioner_pi1";
            url=url+"&scope="+actionArray[1];
            url=url+"&action="+actionArray[0];
            url=url+"&query="+query;
            url=url+"&imagefolder="+imagefolder;
            url=url+"&queryfilter="+JSON.stringify(queryfilter);
            url=url+"&addpeople="+addpeople;
            url=url+"&removepeople="+removepeople;
            url=url+"&categories="+categories;
            url=url+"&lang="+lang;
            url=url+"&sid="+Math.random();
            var newwindow = window.open(url,'dok','height=700,width=700,scrollbars=yes, status=no');
            if (window.focus) {newwindow.focus()}	
            break
        default:
            lista(scope,action,query,lang,hide_search,html_template,imagefolder,addpeople,removepeople,categories,JSON.stringify(queryfilter));
            break
    }
}

function exportDo(scope,action,query,imageSokvag,lang,hide_search,html_template,imagefolder,addpeople,removepeople,categories,queryfilter,pluginid,issiteadmin)
{
    if(action=="exportDepChoice") {
            action="exportDepDo";
    } else {
            action="exportDo";
    }

    var elements = document.institutionerform.elements;
    for (i=0;i<elements.length;i++) {
            if(elements[i].checked && elements[i].name == "rblExportFormat") var checkboxes = elements[i].value;
    }

    var myCheckbox = document.getElementsByName(checkboxes);
    for (ii=0;ii<myCheckbox.length;ii++) {
            if(myCheckbox[ii].checked) var checkContent = true;
    }

    if(checkContent) {
        var checkboxes = 0;
        var rvalue = "";
        var cvalue = "";
        var lextra = "";

        for (var i = 0; i < document.institutionerform.rblExportFormat.length; i++) {

            if(document.institutionerform.rblExportFormat[i].checked) {
                    rvalue = document.institutionerform.rblExportFormat[i].value;
                    checkboxes = document.getElementsByName(rvalue);
            }
        }

        for (var i = 0; i < checkboxes.length; i++) {
                        if(cvalue) cvalue += ",";
                        if(checkboxes[i].checked) cvalue += checkboxes[i].value;
        }
        if(rvalue=="cblLabels") lextra = document.getElementById("txtLabelExtra").value;
        if(rvalue=="cblPdfList") lextra = document.getElementById("txtHeader").value;

        var url="index.php?eID=tx_institutioner_pi1";
        url=url+"&action="+action;
        url=url+"&scope="+scope;
        url=url+"&query="+query;
        url=url+"&queryfilter="+queryfilter;
        url=url+"&addpeople="+addpeople;
        url=url+"&imagefolder="+imagefolder;
        url=url+"&removepeople="+removepeople;
        url=url+"&rvalue="+rvalue;
        url=url+"&cvalue="+cvalue;
        url=url+"&lextra="+lextra;
        url=url+"&lang="+lang;
        url=url+"&categories="+categories;
        url=url+"&sid="+Math.random();

        //console.log(url);

        var newwindow = window.open(url,'dok','height=700,width=700,scrollbars=yes, status=no');
        if (window.focus) {
            newwindow.focus();
        }
    } else {
        alert("Du m&aring;ste kryssa i minst ett alternativ!");	
    }
}

function MoveOptions(sourceElement, targetElement)
{
    var objSourceElement = document.getElementById(sourceElement);
    var objTargetElement = document.getElementById(targetElement);

    var aryTempSourceOptions = new Array();        
    var x = 0;                
    //looping through source element to find selected options        
    for (var i = 0; i < objSourceElement.length; i++) {            
        if (objSourceElement.options[i].selected) {                
            //need to move this option to target element                
            var intTargetLen = objTargetElement.length++;                
            objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text;                
            objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value;           
        } else {               
       //storing options that stay to recreate select element               
            var objTempValues = new Object();               
            objTempValues.text = objSourceElement.options[i].text;                
            objTempValues.value = objSourceElement.options[i].value;                
            aryTempSourceOptions[x] = objTempValues;                
            x++;            
        }        
    }               
    //resetting length of source        
    objSourceElement.length = aryTempSourceOptions.length;        
    //looping through temp array to recreate source select element        
    for (var i = 0; i < aryTempSourceOptions.length; i++) {           
        objSourceElement.options[i].text = aryTempSourceOptions[i].text;            
        objSourceElement.options[i].value = aryTempSourceOptions[i].value;           
        objSourceElement.options[i].selected = false;        
    }   
}

function exportBibtex()
{
    var checkedValues = "";
    for (var i = 0; i < document.institutionerform.bibBox.length; i++) {
        if (document.institutionerform.bibBox[i].checked) {
            if(checkedValues) {
                checkedValues += ",";
            }
            checkedValues += document.institutionerform.bibBox[i].value;
        }
    }

    if(checkedValues) {
        window.open('/typo3conf/ext/institutioner/pi1/bibtex.php?id=' + checkedValues,'Bibtex_export','width=600,height=700,scrollbars=yes');
    } else {
        alert("You must check at least one value!");	
    }
}

function checkAll(checked) {
    if(checked) {
            for (var i = 0; i < document.institutionerform.bibBox.length; i++) {
                    document.institutionerform.bibBox[i].checked = true;
            }
    } else {
            for (var i = 0; i < document.institutionerform.bibBox.length; i++) {
                    document.institutionerform.bibBox[i].checked = false;
            }		
    }
}

function loginbox()
{
     $.fancybox({
        'scrolling'         : 'no',
        'padding'           : 0,
        'centerOnScroll'    : true,
        'href'              : 'index.php?eID=tx_institutioner_pi1&action=loginbox',
        'type'              : 'ajax',
        'maxWidth'    : 800,
        'maxHeight'   : 600,
        'fitToView'   : false,
        'width'       : '70%',
        'height'      : '70%',
        'autoSize'    : false,
        'closeClick'  : false,
        'openEffect'  : 'none',
        'closeEffect' : 'none',
        'showCloseButton' : true,
     });
}

/*! TinySort 1.5.6
* Copyright (c) 2008-2013 Ron Valstar http://tinysort.sjeiti.com/
* License:
*     MIT: http://www.opensource.org/licenses/mit-license.php
*     GPL: http://www.gnu.org/licenses/gpl.html
*/
!function(a,b){"use strict";function c(a){return a&&a.toLowerCase?a.toLowerCase():a}function d(a,b){for(var c=0,d=a.length;d>c;c++)if(a[c]==b)return!e;return e}var e=!1,f=null,g=parseFloat,h=Math.min,i=/(-?\d+\.?\d*)$/g,j=/(\d+\.?\d*)$/g,k=[],l=[],m=function(a){return"string"==typeof a},n=function(a,b){for(var c,d=a.length,e=d;e--;)c=d-e-1,b(a[c],c)},o=Array.prototype.indexOf||function(a){var b=this.length,c=Number(arguments[1])||0;for(c=0>c?Math.ceil(c):Math.floor(c),0>c&&(c+=b);b>c;c++)if(c in this&&this[c]===a)return c;return-1};a.tinysort={id:"TinySort",version:"1.5.6",copyright:"Copyright (c) 2008-2013 Ron Valstar",uri:"http://tinysort.sjeiti.com/",licensed:{MIT:"http://www.opensource.org/licenses/mit-license.php",GPL:"http://www.gnu.org/licenses/gpl.html"},plugin:function(){var a=function(a,b){k.push(a),l.push(b)};return a.indexOf=o,a}(),defaults:{order:"asc",attr:f,data:f,useVal:e,place:"start",returns:e,cases:e,forceStrings:e,ignoreDashes:e,sortFunction:f}},a.fn.extend({tinysort:function(){var p,q,r,s,t=this,u=[],v=[],w=[],x=[],y=0,z=[],A=[],B=function(a){n(k,function(b){b.call(b,a)})},C=function(a,b){return"string"==typeof b&&(a.cases||(b=c(b)),b=b.replace(/^\s*(.*?)\s*$/i,"$1")),b},D=function(a,b){var c=0;for(0!==y&&(y=0);0===c&&s>y;){var d=x[y],f=d.oSettings,h=f.ignoreDashes?j:i;if(B(f),f.sortFunction)c=f.sortFunction(a,b);else if("rand"==f.order)c=Math.random()<.5?1:-1;else{var k=e,o=C(f,a.s[y]),p=C(f,b.s[y]);if(!f.forceStrings){var q=m(o)?o&&o.match(h):e,r=m(p)?p&&p.match(h):e;if(q&&r){var t=o.substr(0,o.length-q[0].length),u=p.substr(0,p.length-r[0].length);t==u&&(k=!e,o=g(q[0]),p=g(r[0]))}}c=d.iAsc*(p>o?-1:o>p?1:0)}n(l,function(a){c=a.call(a,k,o,p,c)}),0===c&&y++}return c};for(p=0,r=arguments.length;r>p;p++){var E=arguments[p];m(E)?z.push(E)-1>A.length&&(A.length=z.length-1):A.push(E)>z.length&&(z.length=A.length)}for(z.length>A.length&&(A.length=z.length),s=z.length,0===s&&(s=z.length=1,A.push({})),p=0,r=s;r>p;p++){var F=z[p],G=a.extend({},a.tinysort.defaults,A[p]),H=!(!F||""===F),I=H&&":"===F[0];x.push({sFind:F,oSettings:G,bFind:H,bAttr:!(G.attr===f||""===G.attr),bData:G.data!==f,bFilter:I,$Filter:I?t.filter(F):t,fnSort:G.sortFunction,iAsc:"asc"==G.order?1:-1})}return t.each(function(c,d){var e,f=a(d),g=f.parent().get(0),h=[];for(q=0;s>q;q++){var i=x[q],j=i.bFind?i.bFilter?i.$Filter.filter(d):f.find(i.sFind):f;h.push(i.bData?j.data(i.oSettings.data):i.bAttr?j.attr(i.oSettings.attr):i.oSettings.useVal?j.val():j.text()),e===b&&(e=j)}var k=o.call(w,g);0>k&&(k=w.push(g)-1,v[k]={s:[],n:[]}),e.length>0?v[k].s.push({s:h,e:f,n:c}):v[k].n.push({e:f,n:c})}),n(v,function(a){a.s.sort(D)}),n(v,function(a){var b=a.s,c=a.n,f=b.length,g=c.length,i=f+g,j=[],k=i,l=[0,0];switch(G.place){case"first":n(b,function(a){k=h(k,a.n)});break;case"org":n(b,function(a){j.push(a.n)});break;case"end":k=g;break;default:k=0}for(p=0;i>p;p++){var m=d(j,p)?!e:p>=k&&k+f>p,o=m?0:1,q=(m?b:c)[l[o]].e;q.parent().append(q),(m||!G.returns)&&u.push(q.get(0)),l[o]++}}),t.length=0,Array.prototype.push.apply(t,u),t}}),a.fn.TinySort=a.fn.Tinysort=a.fn.tsort=a.fn.tinysort}($);