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
		var chapter, page;
		chapter=Book.getChapter();
		page=Book.getPage();
		var iframe = document.getElementsByName("ePubViewerFrame");
        var id = iframe[0].id;
        iframe = document.getElementById(id);
        var idoc = iframe.contentDocument || iframe.contentWindow.document;
        var SelectedText = idoc.getSelection().toString();

        try{ //make array
        	HighlightedStrings[chapter+"-"+page][0];
        }catch(e){
        	HighlightedStrings[chapter+"-"+page] = new Array();
        }


        var userSelection = idoc.getSelection().getRangeAt(0);
        //from dangerous range to safe range(dangerous: include HTML tags, safe: smaller range that doesn't include HTML tags)
        var safeRanges = getSafeRanges(userSelection);

        for(var i = 0; i < safeRanges.length; i++){
        	if(safeRanges[i].toString()){
        		//alert(safeRanges[i].toString());
        		HighlightedStrings[chapter+"-"+page].push(safeRanges[i].toString());
        	}
        	highlightRange(safeRanges[i]);
        }

        //alert("You highlighted : "+ SelectedText);
        //alert("chapterNumber = "+Book.getChapter()+",Page Number ="+Book.getPage());

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

// this function is called when page is changed
function pageChangedFunc(){
	var chapter, page;
	chapter=Book.getChapter();
	page=Book.getPage();

	//bookmark
	if(BookmarkedPages[chapter+"-"+page] == undefined)
		BookmarkedPages[chapter+"-"+page]=false;

	if(BookmarkedPages[chapter+"-"+page] == true) //bookmarked
		document.getElementById("bookmarker").style.visibility="visible";
	else
		document.getElementById("bookmarker").style.visibility="hidden";


	// highlight
	var iframe = document.getElementsByName("ePubViewerFrame");
    var id = iframe[0].id;
    iframe = document.getElementById(id);
    var idoc = iframe.contentDocument || iframe.contentWindow.document;


	for(i=0; 1; i++){
		try{
			var str = HighlightedStrings[chapter+"-"+page][i];
			if (str == undefined) break;
		}
		catch(e){break;}
		$('iframe').contents().find("span:contains('"+str+"')").css( "background-color", "yellow" );
	}

	//memo
	if(memorandum[chapter+"-"+page] != undefined)
		document.getElementById("memoarea").value=memorandum[chapter+"-"+page];
	else
		document.getElementById("memoarea").value="";


	//alert("you opened "+chapter+"-"+page);
}



//to make safe range( that smaller ranges and they dosen't include any HTML tags in it)
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

//to highlight
function highlightRange(range) {
    var newNode = document.createElement("div");
    newNode.setAttribute(
       "style",
       "background-color: yellow; display: inline;"
    );
    range.surroundContents(newNode);
}

function object2json(object){
	try{
		var jsonstring=JSON.stringify(object);
		alert(jsonstring);
		return jsonstring;
	}catch(e){
		// エラーを出力
		alert(e);
	}
}

function memoFunc(){
	if(document.getElementById("memoarea").style.visibility=="visible"){
		document.getElementById("memoarea").style.visibility="hidden";
		document.getElementById("saveBtn").style.visibility="hidden";
		if(memorandum[chapter+"-"+page] != undefined)
			document.getElementById("memoarea").value=memorandum[chapter+"-"+page];
		else
			document.getElementById("memoarea").value="";
	}else{
		document.getElementById("memoarea").style.visibility="visible"
		document.getElementById("saveBtn").style.visibility="visible";
	}
};

function saveMemo(){
	var chapter, page;
	chapter=Book.getChapter();
	page=Book.getPage();

	memorandum[chapter+"-"+page]=document.getElementById("memoarea").value;
	document.getElementById("memoarea").style.visibility="hidden";
	document.getElementById("saveBtn").style.visibility="hidden";
};