# coding: utf-8

""" NOTE: Right now everything assumes json is returned.
And, everything uses json_request, but for most methods
you can have xml or json passed as the _format argument.

I simply didn't have enough time to write a dynamic
way to handle both of these cases. """


""" ADDITIONAL NOTE: In all places where you see the 
@check_collection decorator, these methods naturally 
require a call to the API functions that return the 
data that is used to create collection instances, so
we may start "filling" out the API hierarchy here
without having to worry about any adverse performance
affects. Eventually, this will lead to the entire
schema being filled out in result in O(1) lookup
times in the self.collection (which will have been
set to self.collections_dict at this point....)
dictionary. """

#The URL routing seriously needs to be cleaned up....

import requests, json
import collections, itertools

from querying import QueryPages
from helpers import (check_if_enabled, check_collection,
	optimize_collections, copy_api_methods,
	check_collection_setting,
)
from service_contants import *

MAXIMUM_RECORDS = 1024

#This is overly verbose, but we want to keep it open should
#the Content DM API ever change...
BASE_ROUTER = "dmwebservices/index.php?q=%(service)s"
ROUTER = BASE_ROUTER + "/json"
BASE_ALIAS = BASE_ROUTER + "%(alias)s"
ALIAS_ROUTER = BASE_ALIAS + "/json"
FIELD_VOCABULARY = BASE_ALIAS + "/%(nickname)s/%(forcedict)s/%(forcefullvoc)s/" + "json"

json_request = lambda url: requests.get(url).json()

class Field:

	def __init__(self, collection, field_json):
		
		self.collection = collection
		self.alias = self.collection.alias
		for k, v in field_json.items():
			setattr(self, k, v)

		""" After this the instance namespace (self.__dict__)
		will have the following attributes:

			admin, dc, find, hide, name, nick, readonly,
			req, search, size, type, vocab, vocdb 

		alias is added through self.alias

		Clearly, this would take up quite some space manually.
		Hence, the iterative approach. """

	# TESTED
	@property
	def router(self):

		return self.collection.api.router

	# TESTED
	@staticmethod
	def check_vocabulary_settings(option_name, value):

		""" Just some conversion between types in order to allow some leeway
		in passed arguments. """

		if value is True:
			value = "1"
		elif value is False:
			value = "0"
		elif isinstance(value, int):
			if value not in (0, 1):
				raise ValueError("You may only specify 1 or 0 if passing an " +
					"integer for %s as these equate " % option_name +
					"to True or False respectively.")
			value = str(value)
		elif isinstance(value, str):
			if value not in ("0", "1"):
				raise ValueError("If passing a string for %s " % option_name +
					"the value must be '0' or '1' as these are the only values the " +
					"Content DM API accepts.")
		return value

	# TESTED
	def get_vocab(self, forcedict="0", forcefullvoc="0"):

		""" You can view the documentation for forcedict and forcefullvoc here:

		https://www.oclc.org/support/services/contentdm/help/customizing-website-help/other-customizations/contentdm-api-reference/dmgetcollectionfieldvocabulary.en.html

		By default, we will choose forcedict to be 0, since we don't really care about
		any vocabulary that isn't being used...

		forcefullvoc just returns the entire list of the full controlled vocabulary,
		which we don't really care about either.

		Return Type:

			The API will return an array.

		"""

		#self.vocab designates whether the field has any vocabulary
		#associated with it it is returned from the fields info...
		if self.vocab:
			forcedict = Field.check_vocabulary_settings("forcedict", forcedict)
			forcefullvoc = Field.check_vocabulary_settings("forcefullvoc", forcefullvoc)
			#check_vocabulary_settings is a staticmethod so we must call it through
			#the class itself. Calling 'self.check_vocabulary_settings' would pass a 
			#reference of the current class instance to the function which we don't
			#want.
			url = self.router + FIELD_VOCABULARY
			url %= {
				'service': 'dmGetCollectionFieldVocabulary',
				'alias': self.alias, 'nickname': self.nick, 
				'forcedict': forcedict, 'forcefullvoc': forcefullvoc}
			#This is a generator, by using the generator syntax here 
			#instead of yield allows use to use return.

			#This is similar to the method employed in 
			#ContentDM.get_collection_list
			return (item for item in json_request(url))
		return []

@copy_api_methods(['search', 'get_collection_field_info',
	'get_collection_archival_info', 'get_collection_image_display_settings',
	'get_collection_field_vocabulary', 'get_collection_full_volume_info',
	'get_collection_image_settings', 'get_collection_parameters',
	'get_collection_pdf_info', 'get_collection_words',
	'get_collection_compound_object_info',
	'get_collection_item_parent',
	]
)
class Collection:

	def __init__(self, api, **kwargs):

		self.api = api
		""" After this each Collection instance has the 
		following attrs: alias, name, path, secondary_alias. """
		for k, v in kwargs.items():
			setattr(self, k, v)

		self.items = self.search()

		#This is redundant the only difference from this
		#and the current attributes is an additional 
		#field 'rc' everything else is already an attribute
		#of this instance i.e. - alias, name, path except rc

		# self.parameters = self.get_parameters()

		fields_data = json_request(self.api.router + ALIAS_ROUTER % 
			{'service': 'dmGetCollectionFieldInfo', 
			 'alias': self.alias})

		#This is for quickly looking up whether a find
		#specified is in the collection without having
		#to go and search through the entire fields
		#dict key by key....
		self.finds = []

		#A dictionary is fine here each of these fields 
		#nicknames are unique by their very definition.
		self.fields = collections.OrderedDict()
		self.dublin_core_map = collections.OrderedDict()
		for field_data in fields_data:
			nick = field_data['nick']
			self.fields[nick] = Field(self, field_data)
			_find = field_data['find']
			if _find != 'BLANK':
				self.finds.append(_find)
			self.dublin_core_map[nick] = field_data['dc']

	def __iter__(self):

		return self

	def __next__(self):

		return next(self.items)

class Interface:

	""" This class contains all server-level methods from the api,
	while also providing the collection level functions to keep
	the api wrapper open. You may also use the functions on 
	collection instances which will route through the interfaces
	function passing the instances alias autonomously. """

	def __init__(self, url, port):

		self.router = url + ":" + port + "/"
		#This is a generator, the first time you use it it will
		#be exhausted. Now, if you want a stable iterable element
		#then you can force evaluation of these into a list by 
		#doing list(self.collections) for example.
		self.collection_dict = collections.OrderedDict()
		self.collections = self.get_collection_list()

		# self.collections = collections.OrderedDict()
		# for collection in self.get_collection_list():
			# self.collections[collection.secondary_alias] = collection

	""" Start Server-Level Functions """

	def get_api_version(self):

		url = self.router + ROUTER % {'service': API_VERSION}
		return json_request(url)

	@property
	def dublin_core_fields(self):

		url = self.router + ROUTER % {'service': DUBLIN_CORE_FIELD}
		return json_request(url)

	@property
	def stop_words(self):

		url = self.router + ROUTER % {'service': 'dmGetStopWords', 'format': 'json'}
		return json_request(url)

	def get_locale(self):

		url = self.router + ROUTER % {'service': 'dmGetLocale', 'format': _format}
		return json_request(url)

	""" End Server-Level Functions """

	""" Start Collection-Level Functions """

	def get_collection_list(self, _json=False):

		url = self.router + ROUTER % {'service': COLLECTION_LIST}
		collections = json_request(url)

		""" Note: We had to split the two functions here otherwise
		the return in this function with the yield is just
		syntactic sugar for 'raise StopIteration(collections)'.

		Thus, anytime you called this function were the code block
		in yield_collections at the level of this return statement
		you would have to call next((instance).get_collection_list)
		just to get the returned json list were the _json argument
		True. 

		However, we are instead 'returning' a generator itself here,
		so we avoid the problem altogether. """

		return self.yield_collections(collections) if not _json else collections

	def yield_collections(self, collections):

		for collection in collections:
			#Note, that this has a "/" at the beginning...
			#We are removing it simply so we can use it as the
			#deterministic keyname in the ordereddict without
			#having to worry about it.

			#If you wish to keep this then you may simply append
			#a / at the end of BASE_ALIAS to correct the url
			#routing schema.
			yield Collection(self, **collection)

	@check_if_enabled
	@check_collection_setting('archival_info')
	def get_collection_archival_info(self, alias):

		url = self.router + BASE_ALIAS % {'service': ARCHIVAL_INFO,
		'alias': alias} + "/json"
		print("URL", url)
		return json_request(url)

	@check_if_enabled
	@check_collection_setting('image_display_settings')
	def get_collection_image_display_settings(self, alias):

		url = self.router + BASE_ALIAS % {'service': IMAGE_DISPLAY_SETTINGS,
		'alias': alias} + "/json"
		return json_request(url)

	# TESTED
	@check_collection
	def get_collection_field_info(self, coll):

		return coll.fields

	# TESTED
	def make_collection_from_params(self, alias):

		coll = self.get_collection_parameters(alias)
		path = coll['path']
		alias = "/" + path.split("/")[-1]
		salias = alias[1:]
		name = coll['name']
		#Passed as kwargs here since we're setting the attributes
		#by iterating kwargs.
		return Collection(self, path=path, alias=alias, 
			secondary_alias=salias, name=name)

	# TESTED
	def get_collection_field_vocab(self, coll, nickname, forcedict, forcefullvoc, _format):

		if nickname not in coll.fields:
			raise AttributeError("The nickname %s does " % nickname +
				"not exists within this collection (%s)." %  
				coll.secondary_alias)
		return coll.fields[nickname].get_vocab(forcedict, forcefullvoc, _format)

	# TESTED
	@check_collection
	def get_collection_field_vocabulary(self, coll, *, nickname, forcedict="0", forcefullvoc="0"):

		return self.get_collection_field_vocab(coll, nickname,
			forcedict, forcefullvoc, _format)

	@check_if_enabled
	@check_collection_setting('full_volume_info')
	def get_collection_full_volume_info(self, alias, *, volume):

		url = self.router + BASE_ALIAS % {'service': FULL_VOLUME_INFO,
			'alias': alias} 
		url += "/%s/" % volume + "/json"
		return json_request(url)

	@check_if_enabled
	@check_collection_setting('image_settings')
	def get_collection_image_settings(self, alias):

		url = self.router + ALIAS_ROUTER % {'service': IMAGE_SETTINGS, 'alias': alias}
		return json_request(url)

	#We could technically use @check_collection here, but this would require
	#an entire restructure + feneaggling that's just not happening right now.
	# @check_collection_setting('parameters')
	def get_collection_parameters(self, alias):

		url = self.router + ALIAS_ROUTER % {'service': PARAMETERS, 'alias': alias}
		return json_request(url)

	@check_if_enabled
	@check_collection_setting('pdf_info')
	def get_collection_pdf_info(self, alias):

		url = self.router + ALIAS_ROUTER % {'service': PDF_INFO, 'alias': alias}
		return json_request(url)

	#@check_collection
	@check_collection_setting('words')
	def get_collection_words(self, alias, fields="all"):

		""" Format is json, there is no option for XML here.... """

		# import itertools
		# if fields != "all":
			# return (collection.fields[f].get_vocab("1", "1") for f in fields.split("!"))
		# a = itertools.chain.from_iterable(collection.fields[f].get_vocab("1", "1") for f in collection.fields)
		url = self.router + BASE_ALIAS % {'service': COLL_WORDS, 'alias': alias} + "/" + fields + "/json"
		return json_request(url)

	def check_collection_item_streaming_url(self, alias, find, extension):

		""" Format is json there is no option for XML.... """

		url = self.router + BASE_ALIAS % {'service': 'dmCheckStreamingUrl',
			'alias': alias} + "/" + find + "/json"
		return json_request(url)

	def get_collection_compound_object_info(self, alias, pointer):

		url = self.router + BASE_ALIAS % {'service': COMPOUND_OBJ, 'alias': alias} + "/json"
		return json_request(url)

	def get_item_info(self, alias, pointer):

		url = self.router + BASE_ALIAS % {'service': ITEM_INFO, 'alias':  alias} + "/json"
		return json_request(url)

	def get_item_url(self, alias, find):

		if not find.endswith(".url"):

			raise ValueError("In order to get item urls you must pass " +
				"a 'find' parameter that ends with the '.url' extension."
			)
		return json_request(self.router + BASE_ALIAS % 
			{'service': ITEM_URL, 'alias': alias} + find + "/json"
		)

	def get_collection_item_parent(self, alias, pointer):

		url = self.router + BASE_ALIAS % {'service': PARENT, 'alias': alias}
		url += "/" + alias + "/json"
		return json_request(url)

	def search(self, alias='all', queries='CISOSEARCHALL', 
		fields='title', sort_by='nosort', 
		max_records='1024', start='1', suppress='1', 
		docptr='0', suggest='0', facets="0", showunpub="0", 
		denormalize_facets="0", _format='json'):

		#If the arguments aren't ints and can't be converted
		#to integers...
		is_int = lambda x: isinstance(x, int) or x.isdigit()
		if not (is_int(start) or is_int(max_records)):
			raise ValueError("The start and max_record arguments to " +
				"search must be integers.")
		if int(max_records) > MAXIMUM_RECORDS:
			raise ValueError("The maximum records returned by the API " +
				"cannot exceed %d." % MAXIMUM_RECORDS)

		url = self.router + BASE_ALIAS % {'service': SEARCH, 'alias': alias}
		url += "/" + "/".join([queries, fields, sort_by, 
			str(max_records),
			"%(start)s",
			suppress, docptr, suggest, facets, showunpub, 
			denormalize_facets, _format]
		)
		base = url
		#Simply to get the total records that the QueryPages
		#iterable class is going to need to determine when to stop.

		#NOTE: I have NOT tested the performance here, it may be
		#better to simply return the response == Page 1 for
		#querying.Pages by passing the responses to the constructor
		#and setting an initial self.pages and then updated for 
		#all other pages and returning the second page onwards....
		response = requests.get(url % {'start': str(start)})
		total = response.text.split('"records"')[0].split('"total"')[-1][1:-2]
		total = int(total)
		return QueryPages(self, base, start, max_records, total)

	def check_collection_dict(self, i, collection):

		""" This is for building the entire collection
		hierarchy over time to provide easier access
		since collection objects are created / returned
		a user need not continously create these after
		the first deferrence. They can simply grab them
		from the dictionary... 

		Ideally, all of them would be loaded on the wrapper's
		instantiation but this would take anywhere from secs
		to mins depending on the number of collections.

		Hence, the deferrence. """

		salias = collection.secondary_alias
		if salias not in self.collection_dict:
			self.collection_dict[salias] = collection

	""" NOTE: IF YOU CHOOSE TO REMOVE THE OPTIMIZE
	DECORATORS THEN COPY THE FOLLOWING LINE FROM THE
	DECORATOR:

		cls.collections, generator = itertools.tee(cls.collections, 2)

	Change cls to self obviously. -- self.collections.

	INTO **EACH** OF THE FUNCTIONS AND **REMOVE** THE
	GENERATOR ARGUMENT. Then iterate over 'generator'. """

	@optimize_collections
	def retrieve_collection(self, alias, generator):

		#The getattr is fine here since this code 
		#block is only called if self.collections 
		#isn't a dict, which would result in an
		#error obviously. Hence, the issue is 
		#avoided altogether.
		for i, collection in enumerate(generator):
			#There is no overhead here from the above.
			self.check_collection_dict(i, collection)
			primary = alias.startswith('/')
			param = 'alias' if primary else 'secondary_alias'
			if getattr(collection, param) == alias:
				return collection
		cls.collections = cls.collection_dict

	#Due to the optimization wrapper here, instead of 
	#having to go through all of the layers to get collections
	#or a collection we have a simple O(1) lookup time since 
	#they're in a dict now.

	#The obvious caveat here is that recalling this each time 
	#without the dict replacement will always assure the most 
	#recent collections / items etc. Using the dict doesn't in
	#the general case(s).
	@optimize_collections
	def retrieve_entire_hierarchy(self, generator):

		#We're splitting this into two iterator here so we can keep
		#self.collections for whatever it may potentially be needed
		#for without consuming the generator that is the attribute.
		# self.collections, generator = itertools.tee(self.collections, 2)
		# return generator

		#If we didn't need to check the collection dict we could just 
		#simply return 'generator' here... but we're exhausting the generator
		#and yielding each item to 'recreate' it...

		for i, collection in enumerate(generator):
			#See comment in retrieve_collection
			self.check_collection_dict(i, collection)
			yield collection
