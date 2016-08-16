""" You may dispute the location of this file, and argue that it
should be placed in "archive". But, you may arbitraily extend this
base collection of utilities for other apps, there is no need to
confine it. """

import abc, json, operator, functools

from django.core.exceptions import PermissionDenied
from django.conf import settings

from django.db.models import (Q,
    AutoField, DateField, DateTimeField,
    URLField, ImageField, ForeignKey, OneToOneField, 
    ManyToManyField,
)

#Just so we can get items from the constant file. 
import archive
from responses.views import AjaxGetView

EXCLUDED_MODEL_FIELDS = [
    AutoField, 
    #To be implemented later
    DateTimeField, DateField,
    #We don't want to be searching blobs...
    URLField, ImageField,
    #No. Just no.
    ForeignKey, OneToOneField, ManyToManyField,
]

#This is hideous but works beautifully. JOLIE LAIDE!
get_model_names = lambda model: list(
    map(
        lambda x: x.attname, 
        filter(
            lambda x: not isinstance(x, 
                tuple(EXCLUDED_MODEL_FIELDS)), 
            model._meta.fields
        )
    )
)

def resolve_relations(model, query_list):

    """ A helper method for gather_queries which resolves
    the related objects (excluding M2M -- many to many relations)
    in order to filter through the entire hierarchy at a breadth
    first order level. """ 

    reverse_fks = model._meta.get_all_related_objects()
    for relation in reverse_fks:
        rel_model = relation.related_model
        #This will be the reverse lookup name
        #i.e. - model.filter(reversename__reversefield=*)
        query_name = rel_model.__name__.lower()
        rel_fields = get_model_names(rel_model)
        #Extending the list to include all of these fields
        #in the format of the above comment for the 
        #complex Q objects.
        query_list.extend(
            [query_name + "__" + field for field in rel_fields]
        )

def gather_queries(model):

    """ This is a helper method for facet_queryset in the
    'else' case which means we search 'All' """

    queries = []
    resolve_relations(model, queries)
    queries.extend(get_model_names(model))
    return queries

def facet_queryset(cls):

    """ This is meant to be used with CLASS based view ONLY. 
    All class based view inherently have an object_list attribute.

    Note: If you are using detail views etc, this object_list 
    is by default filtering by the pk/slug_url_kwarg attribute...
    but why would you be trying to implement a search on a detail
    view.....? """

    request = cls.request
    model = cls.model
    if 'query' in request.GET:
        query = request.GET['query']
        _type = request.GET['filter-type']
        if _type == "contributing-institution":
            queryset = model.objects.filter(contributinginstitution__contributing_institution__iexact=query)
        elif _type == "language":
            queryset = model.objects.filter(language__language=query)
        elif _type == "archive":
            queryset = model.objects.filter(archive=query)
        elif _type == "digital-type":
            queryset = model.objects.filter(digitaltype__type_digital=query)
        elif _type == "content-type":
            queryset = model.objects.filter(contenttype__type_digital=query)
        else:
            queries = gather_queries(model)
            #Searches the entire hierarchy tree at a breadth 
            #first-order search using inclusive or.

            #Note: icontains is an inexact contain. 
            queries_ior = [
                Q(**{'%s' % x+"__icontains": query}) for x in queries
            ]
            #We don't want duplicate objects.....
            #distinct() only returns the distinct objects.

            #Equivalent to LIKE %s in SQL.
            queryset = model.objects.filter(
                functools.reduce(operator.or_, queries_ior),
            ).distinct()
    else:
        queryset = model.objects.all()
    return queryset

def search_response(func):
    def wrapper(cls, *args, **kwargs):

        #You could alternatively use isinstance(args[0], type) 
        #since the "class" of a class is a metaclass which
        #is derived from + creates a new type.

        #CAVEAT: This won't work for "old-style" classes in
        #Python 2 that derive from object.
        # if inspect.isclass(args[0]):
            # cls = args[0]
            # request = cls.request
            # model = cls.model
            # facet_class_queryset()
        # else:
            # request = args[0]
            # 
            # facet_method_queryset()
        #cls.object_list is the attr for ListView
        #that determines the objects that are "sent" 
        #to the template template when a user requests
        #the page --> hits the 'get' method.

        #You can view the source code here:

        #https://github.com/django/django/blob/master/django/views/generic/list.py
        
        #get_context_data then sends this object list
        #to the view --> rendered.

        #Hence, if ajax we will simply serialize
        #this object list and send it back or
        #if not ajax, then cool, we just truncated
        #the list as a search should do....

        cls.object_list = facet_queryset(cls)
        return func(cls, *args, **kwargs)
    return wrapper


class BaseSearchGet(AjaxGetView, metaclass=abc.ABCMeta):

    def check_mixin_attributes(self):

        return super().check_mixin_attributes()

    def get_default_response(self):

        #Any request but ajax will be denied, since the 
        #get_default_response is only returned if the 
        #request is not ajax.
        raise PermissionDenied

    @abc.abstractmethod
    def get_choices(self):

        pass

    def get_json(self):

        return {'data': {'data': json.dumps(self.get_choices())},
                'status': 200}

    def get(self, request):

        return self.return_response()  

class GetLanguages(BaseSearchGet):

    def get_choices(self):

        return settings.LANGUAGES

class GetArchives(BaseSearchGet):

    def get_choices(self):

        return archive.constants.ARCHIVE_CHOICES

class GetDigitalTypes(BaseSearchGet):

    def get_choices(self):

        return archive.constants.DIGITAL_TYPE_CHOICES

class GetContributingInstitutions(BaseSearchGet):

    def get_choices(self):

        return archive.constants.INSTITUTION_CHOICES

class GetFileFormats(BaseSearchGet):

    def get_choices(self):

        return archive.constants.FILE_FORMAT_CHOICES

class GetContentTypes(BaseSearchGet):

    def get_choices(self):

        return archive.constants.CONTENT_TYPE_CHOICES

class GetRoles(BaseSearchGet):

    def get_choices(self):

        return archive.constants.ROLE_CHOICES