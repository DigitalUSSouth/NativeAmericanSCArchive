from django.contrib import admin
from .models import *

class EntryModelAdmin(admin.ModelAdmin):
	list_display = ["title", "updated", "created"]
	list_display_links = ["title"]
	list_filter = ["title", "updated", "created"]
	class Meta:
		model = Entry


class LanguageModelAdmin(admin.ModelAdmin):
	list_display = ["_entry", "language"]
	list_display_links = ["_entry"]
	list_filter = ["language"]
	class Meta:
		model = Language


class LCSubjectHeadingModelAdmin(admin.ModelAdmin):
	list_display = ["_entry", "lc_subject"]
	list_display_links = ["_entry"]
	list_filter = ["lc_subject"]
	class Meta:
		model = LCSubjectHeading


class DigitalTypeModelAdmin(admin.ModelAdmin):
	list_display = ["_entry", "type_digital"]
	list_display_links = ["_entry"]
	list_filter = ["type_digital"]
	class Meta:
		model = DigitalType


class RoleModelAdmin(admin.ModelAdmin):
	list_display = ["_entry", "role", "individual_name"]
	list_display_links = ["_entry"]
	list_filter = ["role"]
	class Meta:
		model = Role


class GeographicLocationModelAdmin(admin.ModelAdmin):
	list_display = ["_entry", "geolocation_human",
		"geolocation_machine_latitude", "geolocation_machine_longitude"]
	list_display_links = ["_entry"]
	class Meta:
		model = GeographicLocation


class ContributingInstitutionModelAdmin(admin.ModelAdmin):
	list_display = ["_entry", "contributing_institution"]
	list_display_links = ["_entry"]
	list_filter = ["contributing_institution"]
	class Meta:
		model = ContributingInstitution


class AlternativeTitleModelAdmin(admin.ModelAdmin):
	list_display = ["_entry", "alternative_title"]
	list_display_links = ["_entry"]
	class Meta:
		model = AlternativeTitle

admin.site.register(Entry, EntryModelAdmin)
admin.site.register(Language, LanguageModelAdmin)
admin.site.register(LCSubjectHeading, LCSubjectHeadingModelAdmin)
admin.site.register(DigitalType, DigitalTypeModelAdmin)
admin.site.register(Role, RoleModelAdmin)
admin.site.register(GeographicLocation, GeographicLocationModelAdmin)
admin.site.register(ContributingInstitution, ContributingInstitutionModelAdmin)
admin.site.register(AlternativeTitle, AlternativeTitleModelAdmin)