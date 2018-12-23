#!xscript;

select {
	customers.xml = (name,group_id) where .@id = "1",
	groups.xml = (name,description) where groups[1].role.@id = "6"
};

#Current:
#	$(document).select("filename.xml","xpath statement");