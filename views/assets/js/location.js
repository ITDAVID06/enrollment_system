// JavaScript Data and Functions
const locationData = {
    "Metro Manila": {
        "Caloocan": {
            "zipcode": "1400",
            "barangays": ["Barangay 1", "Barangay 2", "Barangay 3"]
        },
        "Manila": {
            "zipcode": "1000",
            "barangays": ["Barangay 10", "Barangay 11", "Barangay 12"]
        },
        "Makati": {
            "zipcode": "1200",
            "barangays": ["Barangay A", "Barangay B", "Barangay C"]
        }
    },
    "Pampanga": {
        "Angeles": {
            "zipcode": "2009",
            "barangays": ["Barangay 4", "Barangay 5", "Barangay 6"]
        },
        "San Fernando": {
            "zipcode": "2000",
            "barangays": ["Barangay X", "Barangay Y", "Barangay Z"]
        },
        "Mabalacat": {
            "zipcode": "2010",
            "barangays": ["Barangay AA", "Barangay BB", "Barangay CC"]
        }
    },
    "Bulacan": {
        "Malolos": {
            "zipcode": "3000",
            "barangays": ["Barangay 1", "Barangay 2", "Barangay 3"]
        },
        "Meycauayan": {
            "zipcode": "3020",
            "barangays": ["Barangay A", "Barangay B", "Barangay C"]
        },
        "San Jose del Monte": {
            "zipcode": "3023",
            "barangays": ["Barangay J", "Barangay K", "Barangay L"]
        }
    },
    "Batangas": {
        "Batangas City": {
            "zipcode": "4200",
            "barangays": ["Barangay M", "Barangay N", "Barangay O"]
        },
        "Lipa": {
            "zipcode": "4217",
            "barangays": ["Barangay P", "Barangay Q", "Barangay R"]
        },
        "Tanauan": {
            "zipcode": "4232",
            "barangays": ["Barangay S", "Barangay T", "Barangay U"]
        }
    },
    "Cavite": {
        "Dasmariñas": {
            "zipcode": "4114",
            "barangays": ["Barangay V", "Barangay W", "Barangay X"]
        },
        "Bacoor": {
            "zipcode": "4102",
            "barangays": ["Barangay Y", "Barangay Z", "Barangay AA"]
        },
        "Imus": {
            "zipcode": "4103",
            "barangays": ["Barangay AB", "Barangay AC", "Barangay AD"]
        }
    },
    "Laguna": {
        "San Pedro": {
            "zipcode": "4023",
            "barangays": ["Barangay AE", "Barangay AF", "Barangay AG"]
        },
        "Santa Rosa": {
            "zipcode": "4026",
            "barangays": ["Barangay AH", "Barangay AI", "Barangay AJ"]
        },
        "Calamba": {
            "zipcode": "4027",
            "barangays": ["Barangay AK", "Barangay AL", "Barangay AM"]
        }
    },
    "Rizal": {
        "Antipolo": {
            "zipcode": "1870",
            "barangays": ["Barangay AN", "Barangay AO", "Barangay AP"]
        },
        "Cainta": {
            "zipcode": "1900",
            "barangays": ["Barangay AQ", "Barangay AR", "Barangay AS"]
        },
        "Taytay": {
            "zipcode": "1920",
            "barangays": ["Barangay AT", "Barangay AU", "Barangay AV"]
        }
    },
    "Quezon": {
        "Lucena": {
            "zipcode": "4301",
            "barangays": ["Barangay AW", "Barangay AX", "Barangay AY"]
        },
        "Tayabas": {
            "zipcode": "4327",
            "barangays": ["Barangay AZ", "Barangay BA", "Barangay BB"]
        },
        "Sariaya": {
            "zipcode": "4322",
            "barangays": ["Barangay BC", "Barangay BD", "Barangay BE"]
        }
    },
    "Nueva Ecija": {
        "Cabanatuan": {
            "zipcode": "3100",
            "barangays": ["Barangay BF", "Barangay BG", "Barangay BH"]
        },
        "Gapan": {
            "zipcode": "3105",
            "barangays": ["Barangay BI", "Barangay BJ", "Barangay BK"]
        },
        "San Jose": {
            "zipcode": "3121",
            "barangays": ["Barangay BL", "Barangay BM", "Barangay BN"]
        }
    }
};


// Populate provinces once the DOM is loaded
document.addEventListener("DOMContentLoaded", populateProvinces);

function populateProvinces() {
    const provinceSelect = document.getElementById("province");
    for (let province in locationData) {
        let option = document.createElement("option");
        option.value = province;
        option.text = province;
        provinceSelect.appendChild(option);
    }
}

function populateCities() {
    const province = document.getElementById("province").value;
    const citySelect = document.getElementById("city");
    const barangaySelect = document.getElementById("barangay");
    const zipcodeInput = document.getElementById("zipcode");

    citySelect.innerHTML = '<option value="">Select City/Municipality *</option>';
    barangaySelect.innerHTML = '<option value="">Select Barangay *</option>';
    zipcodeInput.value = "";

    if (province && locationData[province]) {
        for (let city in locationData[province]) {
            let option = document.createElement("option");
            option.value = city;
            option.text = city;
            citySelect.appendChild(option);
        }
    }
}

function populateBarangays() {
    const province = document.getElementById("province").value;
    const city = document.getElementById("city").value;
    const barangaySelect = document.getElementById("barangay");
    const zipcodeInput = document.getElementById("zipcode");

    barangaySelect.innerHTML = '<option value="">Select Barangay *</option>';
    zipcodeInput.value = "";

    if (province && city && locationData[province][city]) {
        zipcodeInput.value = locationData[province][city].zipcode;

        const barangays = locationData[province][city].barangays;
        barangays.forEach(barangay => {
            let option = document.createElement("option");
            option.value = barangay;
            option.text = barangay;
            barangaySelect.appendChild(option);
        });
    }
}
