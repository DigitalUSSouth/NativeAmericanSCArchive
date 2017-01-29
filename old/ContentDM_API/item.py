def ItemFactory(api, item_data):

	collection = item_data['collection']
	pointer = item_data['pointer']
	info = api.get_item_info(collection[1:], pointer)
	_object = api.get_compound_object_info(collection[1:], pointer)
	code = _object.get('code', False)
	if code and code == '-2':
		""" If code is in the response dictionary then it means the
		item we requested info for was not compound """
		parent = api.get_parent(collection[1:], pointer)
		if parent['parent'] == "-1":
			item = SinglePageItem(api, info)
		raise LookupError("This is an invalid object.")
	elif 'type' in _object:
	 	_type = _object['type']
	 	if _type in ('Document', 'Document-PDF'):
	 		item = Document(api, collection, pointer, info)
	 	elif _type == 'Monograph':
	 		pass
	 	raise LookupError("This is an invalid object.")
	#Will exist, otherwise error was raised -> doesn't matter.
	return item

class BaseItem:

	pass
	
class Page:

	pass

class Document:

	pass

class CompoundItem:

	""" Compound items end with a .cpd file extension. """
	pass