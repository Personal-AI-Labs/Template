<div class="card">
    <div class="card-header">
        <h2>Typography</h2>
    </div>
    <div class="card-body">
        <h1>Heading 1</h1>
        <p>This is a paragraph. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur et est sed felis aliquet sollicitudin.</p>
        <h2>Heading 2</h2>
        <p>A paragraph with <strong>bold text</strong>, <em>italic text</em>, <code>inline code</code>, and a <a href="#">hyperlink</a>.</p>
        <h3>Heading 3</h3>
        <h4>Heading 4</h4>
        <h5>Heading 5</h5>
        <h6>Heading 6</h6>
        <blockquote>
            This is a blockquote. It's great for pulling out a key statement or quote. It will be styled slightly differently from a standard paragraph.
        </blockquote>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Buttons</h2>
    </div>
    <div class="card-body" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
        <button class="btn btn-primary">Primary Button</button>
        <button class="btn btn-secondary">Secondary Button</button>
        <button class="btn btn-primary btn-sm">Small Button</button>

        <a href="#" class="btn btn-light">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
            </svg>
            <span>Logout Button</span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Lists</h2>
    </div>
    <div class="card-body">
        <div style="display: flex; gap: 4rem;">
            <div>
                <h3>Unordered List</h3>
                <ul>
                    <li>List item one</li>
                    <li>List item two</li>
                    <li>List item three</li>
                    <li>Another item</li>
                </ul>
            </div>
            <div>
                <h3>Ordered List</h3>
                <ol>
                    <li>First list item</li>
                    <li>Second list item</li>
                    <li>Third list item</li>
                    <li>Fourth item</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Form Elements</h2>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="name">Text Input</label>
            <input type="text" id="name" class="form-control" placeholder="Enter your name">
        </div>
        <div class="form-group">
            <label for="comments">Text Area</label>
            <textarea id="comments" class="form-control" placeholder="Leave a comment"></textarea>
        </div>
        <div class="form-group">
            <label for="email-invalid">Invalid Input</label>
            <input type="email" id="email-invalid" class="form-control is-invalid" value="not-an-email">
            <div class="invalid-feedback">
                Please provide a valid email address.
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Alerts & Components</h2>
    </div>
    <div class="card-body">
        <h3>Alerts</h3>
        <div class="alert alert-success">
            <strong>Success!</strong> This is a success alert.
        </div>
        <h3 style="margin-top: 2rem;">Empty State</h3>
        <div class="empty-state">
            <h3>No Items Found</h3>
            <p>You haven't added any items yet. Click the button below to get started.</p>
            <button class="btn">Add New Item</button>
        </div>
    </div>
</div>