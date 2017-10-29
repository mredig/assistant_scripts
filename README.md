<!-- permalink: e5eff4d77167ee6831c79e3c71a80c18 DO NOT DELETE OR EDIT THIS LINE -->
# Scripts Readme

This is an aggregation of scripts I developed in coordination with my [notes to self](https://github.com/mredig/Notes-to-Self). This can be used independent of the notes and vice versa.


### load dependencies

How to get the submodules to load their repositories
* `git submodule init`
* `git submodule update --recursive`
* [reference](https://stackoverflow.com/questions/1535524/git-submodule-inside-of-a-submodule-nested-submodules)
* [updating the repositories](https://stackoverflow.com/questions/8191299/update-a-submodule-to-the-latest-commit)
	* looks like it's a matter of
		1. `git submodule update --remote --merge`
		1. `git add [submodule directory]`
		1. `git commit -m "updated submodules"`
