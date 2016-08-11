from django.conf.urls import include, url

from .views import (SubmitArchiveEntry, ArchiveDirectory, 
	ArchiveEntryDetail,
)

urlpatterns = [
    url(r'^submit-entry/$', SubmitArchiveEntry.as_view(), name='submit_file'),
	url(r'^file/(?P<pk>\d+)/$', ArchiveEntryDetail.as_view(), 
		name='view_entry'),
]