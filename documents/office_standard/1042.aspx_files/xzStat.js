	var sURL = "";
	if ( __xzStat_uuid == null ) 
		__xzStat_uuid = "";
		
	if ( __xzStat_KeyWords == null ) 
		__xzStat_KeyWords = "";
	
	sURL += "UUID=" + escape(__xzStat_uuid);
	sURL += "&amp;";
	sURL += "colorDepth=" + screen.colorDepth;
	sURL += "&amp;";
	sURL += "screen=" + screen.width + "x" + screen.height;
	sURL += "&amp;";
	sURL += "TimeZone=" + (new Date()).getTimezoneOffset()/60;
	sURL += "&amp;";
	sURL += "appName=" + escape(navigator.appName);
	sURL += "&amp;";
	sURL += "referer=" + escape(document.referrer);
	sURL += "&amp;";
	sURL += "title=" + escape(document.title);
	sURL += "&amp;";
	sURL += "language=" + (navigator.systemLanguage?navigator.systemLanguage:navigator.language);;
	sURL += "&amp;";
	sURL += "KeyWords=" + escape(__xzStat_KeyWords);
	
	var sFullURL = "<a href=\"http://www.xiaozhu.com\" target=\"_blank\"><img src=\"http://www.xiaozhu.com/WIC/Pub/xzStat.asp?" + sURL + "\" border=\"0\" /></a>";
	document.write(sFullURL);
	//document.write("<textarea cols=100 rows=10>" + sFullURL + "</textarea>");