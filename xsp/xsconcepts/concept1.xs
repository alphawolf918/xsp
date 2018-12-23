#!xscript;

create {
	file: customers.xml,
	root: info
};

append to /info in customers.xml {
	customer[@id="1"] {
		name["Paul Shannon"],
		country["USA"],
		state["Georgia"],
		city["Byron"],
		age["24"]
	}
};

#OR:

in customers.xml {
	append to /info {
		customer[@id="2"] {
			name["Billy Bob"],
			country["USA"],
			state["Florida"],
			city["Orlando"],
			age["36"]
		}
	}
};

#OR do it with JSON:

var xsp = $('#xsp');
xsp.appendData({
	file: customers.xml,
	data: 'name["Billy Bob"],country["USA"],state["Florida"],city["Orlando"],age["36"]'
});

#This should produce the following:

#<info>
#	<customer id="1">
#		<name>Paul Shannon</name>
#		<country>USA</country>
#		<state>Georgia</state>
#		<city>Byron</city>
#		<age>24</age>
#	</customer>
#</info>