from twisted.web import resource, server, static
import config, urlparse, urllib, urllib2, hashlib, re
import qwebirc.util.rijndael, qwebirc.util.ciphers
import qwebirc.util

class ActivityLogEngine(resource.Resource):
  def __init__(self, prefix):
    self.prefix = prefix

  def render_GET(self, request):
    channel = request.args.get("channel")[0]
    if not channel:
      raise Exception, "Channel not supplied."
    data = urllib.urlencode({'channel' : channel})
    req = urllib2.Request(config.IRCD_ACTIVITY_LOG_URL + '?' + data)
    return urllib2.urlopen(req).read()
