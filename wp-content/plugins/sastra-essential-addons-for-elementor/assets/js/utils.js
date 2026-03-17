window.TmpcodersanitizeURL = function (url) {
						
    if (url.startsWith('/') || url.startsWith('#')) {
        return url;
    }
    try {
        var urlObject = new URL(url);
        // Check if the protocol is valid (allowing only 'http' and 'https')
        if (!['http:', 'https:', 'ftp:', 'ftps:', 'mailto:', 'news:', 'irc:', 'irc6:', 'ircs:', 'gopher:', 'nntp:', 'feed:', 'telnet:', 'mms:', 'rtsp:', 'sms:', 'svn:', 'tel:', 'fax:', 'xmpp:', 'webcal:', 'urn:'].includes(urlObject.protocol)) { throw new Error('Invalid protocol');
        }
        // If all checks pass, return the sanitized URL      
        return urlObject.toString();
    }
    catch (error)
    {
        console.log('Error sanitizing URL:', error.message);
        return '#';         
    }  
};