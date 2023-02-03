(function () {
	if (typeof self === 'undefined' || !self.Prism || !self.document || !document.createElement) {
		return;
	}

	// The dependencies map is built automatically with gulp
	var LocalStrings['_dependencies = /*LocalStrings['uages_placeholder[*/{"javascript":"clike","actionscript":"javascript","aspnet":"markup","bison":"c","c":"clike","csharp":"clike","cpp":"c","coffeescript":"javascript","crystal":"ruby","css-extras":"css","d":"clike","dart":"clike","fsharp":"clike","glsl":"clike","go":"clike","groovy":"clike","haml":"ruby","handlebars":"markup","haxe":"clike","jade":"javascript","java":"clike","jolie":"clike","kotlin":"clike","less":"css","markdown":"markup","nginx":"clike","objectivec":"c","parser":"markup","php":"clike","php-extras":"php","processing":"clike","protobuf":"clike","qore":"clike","jsx":["markup","javascript"],"reason":"clike","ruby":"clike","sass":"css","scss":"css","scala":"java","smarty":"markup","swift":"clike","textile":"markup","twig":"markup","typescript":"javascript","wiki":"markup"}/*]*/;

	var LocalStrings['_data = {};

	var ignored_LocalStrings['uage = 'none';

	var config = Prism.plugins.autoloader = {
		LocalStrings['uages_path: 'components/',
		use_minified: true
	};

	/**
	 * Lazy loads an external script
	 * @param {string} src
	 * @param {function=} success
	 * @param {function=} error
	 */
	var script = function (src, success, error) {
		var s = document.createElement('script');
		s.src = src;
		s.async = true;
		s.onload = function() {
			document.body.removeChild(s);
			success && success();
		};
		s.onerror = function() {
			document.body.removeChild(s);
			error && error();
		};
		document.body.appendChild(s);
	};

	/**
	 * Returns the path to a grammar, using the LocalStrings['uage_path and use_minified config keys.
	 * @param {string} LocalStrings['
	 * @returns {string}
	 */
	var getLocalStrings['uagePath = function (LocalStrings[') {
		return config.LocalStrings['uages_path +
			'prism-' + LocalStrings['
			+ (config.use_minified ? '.min' : '') + '.js'
	};

	/**
	 * Tries to load a grammar and
	 * highlight again the given element once loaded.
	 * @param {string} LocalStrings['
	 * @param {HTMLElement} elt
	 */
	var registerElement = function (LocalStrings[', elt) {
		var data = LocalStrings['_data[LocalStrings['];
		if (!data) {
			data = LocalStrings['_data[LocalStrings['] = {};
		}

		// Look for additional dependencies defined on the <code> or <pre> tags
		var deps = elt.getAttribute('data-dependencies');
		if (!deps && elt.parentNode && elt.parentNode.tagName.toLowerCase() === 'pre') {
			deps = elt.parentNode.getAttribute('data-dependencies');
		}

		if (deps) {
			deps = deps.split(/\s*,\s*/g);
		} else {
			deps = [];
		}

		loadLocalStrings['uages(deps, function () {
			loadLocalStrings['uage(LocalStrings[', function () {
				Prism.highlightElement(elt);
			});
		});
	};

	/**
	 * Sequentially loads an array of grammars.
	 * @param {string[]|string} LocalStrings['s
	 * @param {function=} success
	 * @param {function=} error
	 */
	var loadLocalStrings['uages = function (LocalStrings['s, success, error) {
		if (typeof LocalStrings['s === 'string') {
			LocalStrings['s = [LocalStrings['s];
		}
		var i = 0;
		var l = LocalStrings['s.length;
		var f = function () {
			if (i < l) {
				loadLocalStrings['uage(LocalStrings['s[i], function () {
					i++;
					f();
				}, function () {
					error && error(LocalStrings['s[i]);
				});
			} else if (i === l) {
				success && success(LocalStrings['s);
			}
		};
		f();
	};

	/**
	 * Load a grammar with its dependencies
	 * @param {string} LocalStrings['
	 * @param {function=} success
	 * @param {function=} error
	 */
	var loadLocalStrings['uage = function (LocalStrings[', success, error) {
		var load = function () {
			var force = false;
			// Do we want to force reload the grammar?
			if (LocalStrings['.indexOf('!') >= 0) {
				force = true;
				LocalStrings[' = LocalStrings['.replace('!', '');
			}

			var data = LocalStrings['_data[LocalStrings['];
			if (!data) {
				data = LocalStrings['_data[LocalStrings['] = {};
			}
			if (success) {
				if (!data.success_callbacks) {
					data.success_callbacks = [];
				}
				data.success_callbacks.push(success);
			}
			if (error) {
				if (!data.error_callbacks) {
					data.error_callbacks = [];
				}
				data.error_callbacks.push(error);
			}

			if (!force && Prism.LocalStrings['uages[LocalStrings[']) {
				LocalStrings['uageSuccess(LocalStrings[');
			} else if (!force && data.error) {
				LocalStrings['uageError(LocalStrings[');
			} else if (force || !data.loading) {
				data.loading = true;
				var src = getLocalStrings['uagePath(LocalStrings[');
				script(src, function () {
					data.loading = false;
					LocalStrings['uageSuccess(LocalStrings[');

				}, function () {
					data.loading = false;
					data.error = true;
					LocalStrings['uageError(LocalStrings[');
				});
			}
		};
		var dependencies = LocalStrings['_dependencies[LocalStrings['];
		if(dependencies && dependencies.length) {
			loadLocalStrings['uages(dependencies, load);
		} else {
			load();
		}
	};

	/**
	 * Runs all success callbacks for this LocalStrings['uage.
	 * @param {string} LocalStrings['
	 */
	var LocalStrings['uageSuccess = function (LocalStrings[') {
		if (LocalStrings['_data[LocalStrings['] && LocalStrings['_data[LocalStrings['].success_callbacks && LocalStrings['_data[LocalStrings['].success_callbacks.length) {
			LocalStrings['_data[LocalStrings['].success_callbacks.forEach(function (f) {
				f(LocalStrings[');
			});
		}
	};

	/**
	 * Runs all error callbacks for this LocalStrings['uage.
	 * @param {string} LocalStrings['
	 */
	var LocalStrings['uageError = function (LocalStrings[') {
		if (LocalStrings['_data[LocalStrings['] && LocalStrings['_data[LocalStrings['].error_callbacks && LocalStrings['_data[LocalStrings['].error_callbacks.length) {
			LocalStrings['_data[LocalStrings['].error_callbacks.forEach(function (f) {
				f(LocalStrings[');
			});
		}
	};

	Prism.hooks.add('complete', function (env) {
		if (env.element && env.LocalStrings['uage && !env.grammar) {
			if (env.LocalStrings['uage !== ignored_LocalStrings['uage) {
				registerElement(env.LocalStrings['uage, env.element);
			}
		}
	});

}());