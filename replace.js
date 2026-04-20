import fs from 'fs';
import path from 'path';

const directoryPath = path.join(process.cwd(), 'resources', 'views');

function walk(dir) {
    let results = [];
    const list = fs.readdirSync(dir);
    list.forEach(function(file) {
        file = path.join(dir, file);
        const stat = fs.statSync(file);
        if (stat && stat.isDirectory()) { 
            results = results.concat(walk(file));
        } else { 
            if (file.endsWith('.blade.php')) {
                results.push(file);
            }
        }
    });
    return results;
}

const replacements = [
    { target: /text-blue-600/g, replacement: 'text-orange-500' },
    { target: /text-blue-800/g, replacement: 'text-orange-700' },
    { target: /text-cyan-600/g, replacement: 'text-orange-500' },
    { target: /hover:text-blue-800/g, replacement: 'hover:text-orange-600' },
    { target: /hover:text-blue-500/g, replacement: 'hover:text-orange-400' },
    { target: /bg-blue-100/g, replacement: 'bg-orange-100' },
    { target: /bg-blue-50/g, replacement: 'bg-orange-50' },
    { target: /bg-blue-200/g, replacement: 'bg-orange-200' },
    { target: /bg-blue-300/g, replacement: 'bg-orange-300' },
    { target: /bg-cyan-50/g, replacement: 'bg-orange-50' },
    { target: /text-cyan-400/g, replacement: 'text-orange-400' },
    { target: /from-blue-600 to-cyan-500/g, replacement: 'orange-500' },
    { target: /from-blue-600 to-cyan-400/g, replacement: 'orange-500' },
    { target: /from-blue-600\/10/g, replacement: 'orange-500/10' },
    { target: /shadow-blue-500\/30/g, replacement: 'shadow-orange-500/30' },
    { target: /shadow-blue-500\/40/g, replacement: 'shadow-orange-500/40' },
    { target: /bg-gradient-to-br from-blue-600 to-cyan-500/g, replacement: 'bg-orange-500' },
    { target: /bg-gradient-to-br from-blue-600 to-cyan-400/g, replacement: 'bg-orange-500' },
    { target: /bg-gradient-to-r from-blue-600 to-cyan-500/g, replacement: 'bg-orange-500' },
    { target: /hover:from-blue-700 hover:to-cyan-600/g, replacement: 'hover:bg-orange-600' },
    { target: /border-l-blue-500/g, replacement: 'border-l-orange-500' },
    { target: /bg-blue-600/g, replacement: 'bg-orange-500' },
    { target: /hover:bg-blue-700/g, replacement: 'hover:bg-orange-600' },
    { target: /hover:bg-blue-50/g, replacement: 'hover:bg-orange-50' },
    { target: /border-blue-100/g, replacement: 'border-orange-100' },
    { target: /border-blue-200/g, replacement: 'border-orange-200' },
];

try {
    const results = walk(directoryPath);
    let modifiedFiles = 0;
    results.forEach(file => {
        let content = fs.readFileSync(file, 'utf8');
        let originalContent = content;
        
        replacements.forEach(r => {
            content = content.replace(r.target, r.replacement);
        });

        // specific edge case fixing
        content = content.replace(/text-transparent bg-clip-text bg-gradient-to-r orange-500/g, "text-orange-500");
        content = content.replace(/bg-gradient-to-tr orange-500/g, "bg-orange-500");
        content = content.replace(/bg-gradient-to-tr orange-500\/10/g, "bg-orange-500/10");
        content = content.replace(/shadow-blue-500/g, "shadow-orange-500");
        
        if (content !== originalContent) {
            fs.writeFileSync(file, content, 'utf8');
            console.log("Updated: " + file);
            modifiedFiles++;
        }
    });
    console.log(`Finished updating ${modifiedFiles} files.`);
} catch (e) {
    console.error(e);
}
