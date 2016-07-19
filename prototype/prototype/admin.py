from django.contrib import admin
from .models import *

admin.site.register(Document)
admin.site.register(Role)
admin.site.register(GeographicLocationMachine)
admin.site.register(GeographicLocationHuman)
admin.site.register(ShelfMark)
admin.site.register(ContributingInstitution)
admin.site.register(AlternativeTitle)
