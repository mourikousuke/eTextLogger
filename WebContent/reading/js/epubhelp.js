// get year/month/day/hours/minutes/seconds
function getTime(){
	var DD = new Date();
	var Year = 1900 + DD.getYear();
	var Month = DD.getMonth() + 1;
	var Day = DD.getDate();
    var Hours = DD.getHours();
    var Minutes = DD.getMinutes();
    var Seconds = DD.getSeconds();
    alert(Month+"/"+Day+"("+Year+")---"+Hours+"H"+Minutes+"M"+Seconds+"S");
}

//Hightlight strings in iframe. To use this method, you need to name iframe "ePubViewerFrame" (in js/epub.min.js)
function HighlightString() {
	try{
		var iframe = document.getElementsByName("ePubViewerFrame");
        var id = iframe[0].id;
        iframe = document.getElementById(id);
        var idoc = iframe.contentDocument || iframe.contentWindow.document;
        var SelectedText = idoc.getSelection().toString();
        /*
        var nNd = document.createElement("highlighted");

        var w = idoc.getSelection().getRangeAt(0);
        w.surroundContents(nNd);


        //var SelectedText = nNd.innerHTML; //get selected strings -> SelectedText

        $('iframe').contents().find('highlighted').css('background-color', 'yellow');
		getTime();

*/
        var userSelection = idoc.getSelection().getRangeAt(0);
        var safeRanges = getSafeRanges(userSelection);
        for (var i = 0; i < safeRanges.length; i++) {
            highlightRange(safeRanges[i]);
        }



        alert("You highlighted : "+ SelectedText);

        alert("chapterNumber = "+Book.getChapter()+",Page Number ="+Book.getPage());

	}catch(e){
		alert("error");
	}
}

function BookmarkPage(){
	var chapter, page;
	chapter=Book.getChapter();
	page=Book.getPage();

	if(BookmarkedPages[chapter+'-'+page] == true){
		BookmarkedPages[chapter+'-'+page]=false;
		//alert("BOOKMARK=FALSE chapterNumber = "+chapter+",Page Number ="+page);
	}else{
		BookmarkedPages[chapter+'-'+page] = true;
		//alert("BOOKMARK=TRUE chapterNumber = "+chapter+",Page Number ="+page);
	}

	if(BookmarkedPages[chapter+"-"+page] == true)
		document.getElementById("bookmarker").style.visibility="visible";
	else
		document.getElementById("bookmarker").style.visibility="hidden";
}

// switch bookmark's visible/hidden (when page is turned)
function pageChange(str){
	var chapter, page;
	chapter=Book.getChapter();
	page=Book.getPage();
	var a = BookmarkedPages[chapter+"-"+page];
	if(a == undefined)
		BookmarkedPages[chapter+"-"+page]=false;

	var lastPage=1;
	if(page==1 && str == "prev"){ // look for previous page's number of last page
		while(1){
			var c = chapter-1;
			var d = lastPage+1;
			var b = BookmarkedPages[c+"-"+d];
			if(b != undefined)
				lastPage++;
			else
				break;
		}
	}
	var flag=false;
	var pi=page+1;
	var pd=page-1;
	var ci=chapter+1;
	var cd=chapter-1;
	if(str == "prev"){ //prev
		if(page==1){ //no previous page
			if(BookmarkedPages[cd+"-"+lastPage] == true) //bookmarked
				document.getElementById("bookmarker").style.visibility="visible";
			else if(chapter!=0) //not bookmarked and not first page
				document.getElementById("bookmarker").style.visibility="hidden";
		}else{ // previous page exists
			if(BookmarkedPages[chapter+"-"+pd] == true)
				document.getElementById("bookmarker").style.visibility="visible";
			else
				document.getElementById("bookmarker").style.visibility="hidden";
		}
	}else if(str == "next"){
		if(BookmarkedPages[chapter+"-"+pi]==undefined){ // next page may not exist
			if(BookmarkedPages[ci+"-1"] == true)
				document.getElementById("bookmarker").style.visibility="visible";
			else
				document.getElementById("bookmarker").style.visibility="hidden";
		}else{ // next page exists
			if(BookmarkedPages[chapter+"-"+pi] == true)
				document.getElementById("bookmarker").style.visibility="visible";
			else
				document.getElementById("bookmarker").style.visibility="hidden";
		}
	}
}

//to highlight (1)
function getSafeRanges(dangerous) {
    var a = dangerous.commonAncestorContainer;
    // Starts -- Work inward from the start, selecting the largest safe range
    var s = new Array(0), rs = new Array(0);
    if (dangerous.startContainer != a)
        for(var i = dangerous.startContainer; i != a; i = i.parentNode)
            s.push(i)
    ;
    if (0 < s.length) for(var i = 0; i < s.length; i++) {
        var xs = document.createRange();
        if (i) {
            xs.setStartAfter(s[i-1]);
            xs.setEndAfter(s[i].lastChild);
        }
        else {
            xs.setStart(s[i], dangerous.startOffset);
            xs.setEndAfter(
                (s[i].nodeType == Node.TEXT_NODE)
                ? s[i] : s[i].lastChild
            );
        }
        rs.push(xs);
    }

    // Ends -- basically the same code reversed
    var e = new Array(0), re = new Array(0);
    if (dangerous.endContainer != a)
        for(var i = dangerous.endContainer; i != a; i = i.parentNode)
            e.push(i)
    ;
    if (0 < e.length) for(var i = 0; i < e.length; i++) {
        var xe = document.createRange();
        if (i) {
            xe.setStartBefore(e[i].firstChild);
            xe.setEndBefore(e[i-1]);
        }
        else {
            xe.setStartBefore(
                (e[i].nodeType == Node.TEXT_NODE)
                ? e[i] : e[i].firstChild
            );
            xe.setEnd(e[i], dangerous.endOffset);
        }
        re.unshift(xe);
    }

    // Middle -- the uncaptured middle
    if ((0 < s.length) && (0 < e.length)) {
        var xm = document.createRange();
        xm.setStartAfter(s[s.length - 1]);
        xm.setEndBefore(e[e.length - 1]);
    }
    else {
        return [dangerous];
    }

    // Concat
    rs.push(xm);
    response = rs.concat(re);

    // Send to Console
    return response;
}

// to highlight (2)
function highlightSelection() {
    var userSelection = window.getSelection().getRangeAt(0);
    highlightRange(userSelection);

}

// to highlight (3)
function highlightRange(range) {
    var newNode = document.createElement("div");
    newNode.setAttribute(
       "style",
       "background-color: yellow; display: inline;"
    );
    range.surroundContents(newNode);
}