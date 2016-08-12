from django.conf.urls import include, url

from .views import (SubmitArchiveEntry, ArchiveDirectory, 
	ArchiveEntryDetail,
)

urlpatterns = [
    url(r'^submit-entry/$', SubmitArchiveEntry.as_view(), 
    	name='submit_archive_entry'),
	url(r'^entry/(?P<pk>\d+)/$', ArchiveEntryDetail.as_view(), 
		name='archive_entry_detail'),
]