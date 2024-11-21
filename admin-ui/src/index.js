import React from 'react';
import ReactDOM, {createRoot} from 'react-dom/client';
import './index.css';
import App from './App';

let target = document.getElementById("wlr-point-remainder");
if (target) {
    let root=createRoot(target);
    root.render(<App />);
} else {
    setTimeout(() => {
        target = document.getElementById("wlr-point-remainder");
        if (target) {
            let root=createRoot(target);
            root.render(<App />);
        }
    }, 1000)
}
