"""prototype URL Configuration

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/1.8/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  url(r'^$', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  url(r'^$', Home.as_view(), name='home')
Including another URLconf
    1. Add an import:  from blog import urls as blog_urls
    2. Add a URL to urlpatterns:  url(r'^blog/', include(blog_urls))
"""
from django.conf.urls import include, url
from django.contrib import admin

from django.conf import settings
from django.conf.urls.static import static

from .views import DocumentWizard, DocumentDetail, DocumentDirectory, INSTANCE_DICT

urlpatterns = [
    url(r'^admin/', include(admin.site.urls)),
    url(r'^$', "prototype.views.home", name="home"),
    url(r'^submit-document/$', DocumentWizard.as_view(instance_dict=INSTANCE_DICT),
    	name="submit_document"),
    url(r'^document/(?P<pk>\d+)$', DocumentDetail.as_view(),
        name='document_detail'),
    # url(r'^document/(?P<pk>\d+)/read$', something_here,
        # name='document_read'),
    url(r'^documents/$', DocumentDirectory.as_view(),
        name='document_directory'),

]   +	static(settings.STATIC_URL, document_root=settings.STATIC_ROOT)
