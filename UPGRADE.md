# Upgrade guide

- [Upgrading to 1.1.0 from 1.0.1](#upgrade-1.1.0)

<a name="upgrade-1.1.0"></a>
## Upgrading To 1.1.0

The Google TTS plugin is redesigned for multiple usage in same page fix. When you update plugin to `1.1.0`, you should change your component code in CMS pages or partials (or anywhere else)

New usage example:

	{% component 'TTS' lang='en' sentence='One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin. "What has happened!?" he asked himself. "I... don\'t know." said Samsa, "Maybe this is a bad dream." He lay on his armour-like back, and if he lifted his head a little he could see his brown belly, slightly domed and divided by arches into stiff sections.' %}
