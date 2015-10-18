$(function() {
    $('.jsLinkHidingIndexing').each(function(linkID, linkObj) {
        var self = $(linkObj);
        if(self.data('link')) self.attr('href', self.data('link')).removeAttr('data-link');
    });
});