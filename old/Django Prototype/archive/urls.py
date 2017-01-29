from django.conf.urls import include, url

from .views import (SubmitArchiveEntry, ArchiveEntryDetail,
	ArchiveDirectory,
	ArchiveTimeline,
)

urlpatterns = [
	url(r'^$', ArchiveDirectory.as_view(), name='archive_directory'),
	# url(r'^search/$', 
		# ArchiveDirectorySearch.as_view(), 
		# name='archive_directory_search'),
    url(r'^submit-entry/$', SubmitArchiveEntry.as_view(), 
    	name='submit_archive_entry'),
	url(r'^entry/(?P<pk>\d+)/$', ArchiveEntryDetail.as_view(), 
		name='archive_entry_detail'),
	url(r'^timeline/$', ArchiveTimeline.as_view(),
		name='timeline'),
]