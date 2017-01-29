import requests, math, itertools

from item import ItemFactory

ITEMS = lambda self, y: map(lambda x: ItemFactory(self.api, x), y)

class QueryPages:

	def __init__(self, api, base, start, max_records, total):

		self.api = api
		self.base = base
		self.start, self.max_records = int(start), int(max_records)
		self.total = total

	def __iter__(self):

		return self

	def __next__(self):

		if self.total:
			if (self.start + self.max_records) < self.total:
				self.start += self.max_records
				new_response = requests.get(self.base % 
					{'start': str(self.start)}
				).json()
				return ITEMS(self, new_response['records'])
			raise StopIteration
		raise StopIteration