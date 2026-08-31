import os
import shutil
import subprocess
import glob

base_dir = "/home/mirukibs/Development/TKCC-Project/TKCC-V2/Docs"
modules_dir = os.path.join(base_dir, "Modules")
assets_dir = os.path.join(base_dir, "assets")

os.makedirs(assets_dir, exist_ok=True)

modules = ["Members", "Households", "Communities"]

for mod in modules:
    mod_dir = os.path.join(modules_dir, mod)
    mod_asset_dir = os.path.join(assets_dir, mod)
    os.makedirs(mod_asset_dir, exist_ok=True)
    
    if os.path.exists(mod_dir):
        # Move all .puml files
        for puml in glob.glob(os.path.join(mod_dir, "*.puml")):
            shutil.copy(puml, mod_asset_dir)
            
        # Combine md files
        md_files = sorted(glob.glob(os.path.join(mod_dir, "*.md")))
        combined_md = f"# {mod} Module\n\n"
        for md in md_files:
            with open(md, "r") as f:
                content = f.read()
                # Rewrite image paths from ./Image.svg to ./assets/{mod}/Image.svg
                content = content.replace("./", f"./assets/{mod}/")
                combined_md += content + "\n\n"
        
        with open(os.path.join(base_dir, f"{mod}.md"), "w") as f:
            f.write(combined_md)
