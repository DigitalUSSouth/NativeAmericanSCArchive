from django.shortcuts import render
from archive.models import File

def home(request):

    ctx = {'num_docs': File.objects.all().count()}
    return render(request, "base.html", ctx)