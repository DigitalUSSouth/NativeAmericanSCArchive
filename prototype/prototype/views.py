from django.shortcuts import render
from .models import *

def home(request):

    ctx = {'num_docs': File.objects.all().count()}
    return render(request, "base.html", ctx)