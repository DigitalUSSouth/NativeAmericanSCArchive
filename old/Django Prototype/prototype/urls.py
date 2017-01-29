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

from .views import home, about, contact

from search_utils import (
    GetLanguages, GetRoles, 
    GetDigitalTypes,GetContentTypes, GetPhysicalTypes,
    GetContributingInstitutions, GetCollections,
    GetFileFormats, GetGenres,
    GetCopyrightHolders,
)

urlpatterns = [
    url(r'^$', home, name="home"),
    url(r'^get-languages/$', GetLanguages.as_view(), name="get_languages"),
    url(r'^get-collections/$', GetCollections.as_view(), name="get_collections"),
    url(r'^get-digital-types/$', GetDigitalTypes.as_view(), name="get_digital_types"),
    url(r'^get-physical-types/$', GetPhysicalTypes.as_view(), name="get_physical_types"),
    url(r'^get-content-types/$', GetContentTypes.as_view(), name="get_content_types"),
    url(r'^get-contributing-institutions/$', GetContributingInstitutions.as_view(), name="get_contributing_institutions"),
    url(r'^get-roles/$', GetRoles.as_view(), name="get_roles"),
    url(r'^get-file-formats/$', GetFileFormats.as_view(), name="get_file_formats"),
    url(r'^get-genres/$', GetGenres.as_view(), name="get_genres"),
    url(r'^get-copyright-holders/$', GetCopyrightHolders.as_view(), name="get_copyright_holders"),
    url(r'^about/$', about, name='about'),
    url(r'^contact/$', contact, name='contact_us'),
    url(r'^admin/', include(admin.site.urls)),
    url(r'^accounts/', include('registration.urls')),
    url(r'^archive/', include('archive.urls')),
]   +	static(settings.STATIC_URL, document_root=settings.STATIC_ROOT)
