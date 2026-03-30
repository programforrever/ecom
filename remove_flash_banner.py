#!/usr/bin/env python3
import re

filepath = 'resources/views/backend/website_settings/pages/nuevotema/home_page_edit.blade.php'

with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Remove Flash Deal Banner Section completely
# Match from <!-- Flash Deal Banner Section --> to <!-- Category Wise Products -->
pattern = r'<!--\s*Flash Deal Banner Section\s*-->.*?(?=<!--\s*Category Wise Products\s*-->)'

content = re.sub(pattern, '', content, flags=re.DOTALL)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print("Flash Deal Banner section removed successfully")
