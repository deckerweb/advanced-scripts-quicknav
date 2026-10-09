#!/usr/bin/env python3
"""Generate bilingual public documents from docs/content.json."""
import json
from pathlib import Path
root=Path(__file__).resolve().parents[1]
for name,text in json.loads((root/'docs/content.json').read_text()).items():
 path=root/name
 if not path.resolve().is_relative_to(root.resolve()): raise ValueError('Invalid document path')
 path.parent.mkdir(parents=True,exist_ok=True)
 path.write_text(text)
