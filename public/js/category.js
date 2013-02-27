/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var req;
var isIE;

function init() {
    completeField = document.getElementById("category1");
}

function getSubcategory() {
    var url = "/blogPost/subcategory?action=complete&ParentId=" + escape(category1.value);
    req = initRequest();
    req.open("GET", url, true);
    req.onreadystatechange = callback;
    req.send(null);

}

function getSubcategory1() {
    var url = "/blogPost/subcategory?action=complete&ParentId=" + escape(category2.value);
    req = initRequest();
    req.open("GET", url, true);
    req.onreadystatechange = callback1;
    req.send(null);

}

function initRequest() {
    if (window.XMLHttpRequest) {
        if (navigator.userAgent.indexOf('MSIE') != -1) {
            isIE = true;
        }
        return new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        isIE = true;
        return new ActiveXObject("Microsoft.XMLHTTP");
    }
}

function callback() {
    console.log(req);
    if (req.readyState == 4) {
        if (req.status == 200) {
            
            parseMessages(req.response);
        }
    }
}

function callback1() {
    console.log(req);
    if (req.readyState == 4) {
        if (req.status == 200) {
            
            parseMessages1(req.response);
        }
    }
}
function parseMessages1(response) {
    
    if (response == null) {
        
        return false;
    } else {
        
        
        select = $(' #category3 ');
        select.empty();
        data = response;
        data = JSON.parse(response);
        for (var i=0; i<data.length; i++) {
            select.append('<option value="' + data[i].CategoryId + '">' + data[i].CategoryName + '</option>');
        }
    }

}

function parseMessages(response) {
    
    if (response == null) {
        
        return false;
    } else {
        
        
        select = $(' #category2 ');
        select.empty();
        data = response;
        data = JSON.parse(response);
        for (var i=0; i<data.length; i++) {
            select.append('<option value="' + data[i].CategoryId + '">' + data[i].CategoryName + '</option>');
        }
    }

}

    
